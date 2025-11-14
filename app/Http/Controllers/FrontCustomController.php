<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Utils\CartManager;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Traits\SendSmsTraits;
use App\Models\BkashPaymentRecord;
use Karim007\LaravelNagad\Facade\NagadPayment;
use Karim007\LaravelNagad\Facade\NagadRefund;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class FrontCustomController extends Controller
{
    use SendSmsTraits;

    public function updateQuantity(Request $request)
    {
        $cartId = $request->cart_id;
        $quantity = $request->quantity;

        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            return response()->json(['success' => true, 'message' => 'Cart updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
    }

    public function deleteItem(Request $request)
    {
        $cartItem = Cart::find($request->cart_id);

        if ($cartItem) {
            $cartItem->delete();

            if (session()->has('coupon_discount')) {
                session()->forget('coupon_discount');
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'reload' => true // ✅ this is needed for JS
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found.'
        ], 404);
    }


    public function checkout(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => [
                'required',
                'regex:/^(?:\+?88)?01[3-9]\d{8}$/',
            ],
            'email' => 'nullable|email',
            'address' => 'required',
        ]);

        $carts = Cart::where('customer_id', $request->customer_id)->get();

        $cart_total = 0;
        foreach ($carts as $cart) {

            $cart_total += ($cart->price + $cart->tax - $cart->discount) * $cart->quantity;
        }

        $shipping_cost = $request->destination == 'outside_dhaka' ? 120 : 70;
        $total_amount = $cart_total + $shipping_cost - $request->discount;


        $phone = $this->filterPhoneNumber($request->phone);

        // Customer Create
        if (auth('customer')->user() == null) {
            $customer = User::where('phone', $phone)->first();
            if (!$customer) {
                $password = rand(10000000, 99999999);
                $customer = User::create([
                    'name' => $request->name,
                    'f_name' => $request->name,
                    'email' => $request->email,
                    'phone' => $phone,
                    'password' => bcrypt($password),
                    'type' => 'customer',
                ]);
                $phone = $customer->phone;
                $message = "Welcome to Alziro! \n\nAccess your account at https://alziro.com/customer/login . \nEnter your phone number and Login Password: {$password} .";
                $this->sendSms($phone, $message);
            }
        } else {

            $customer = auth('customer')->user();
        }


        $invoice_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Cash On Delivery
        if ($request->payment_method == 'cod') {
            $order_id = 100000 + Order::all()->count() + 1;
            if (Order::find($order_id)) {
                $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
            }

            $order = Order::create([
                'id' => $order_id,
                'invoice_id' => $invoice_id,
                'customer_id' => $customer->id,
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'order_amount' => $total_amount,
                'shipping_cost' => $shipping_cost,
                'shipping_address' => $request->address,
                'order_type' => 'web',
                'seller_id' => 0,
                'discount_amount' => $request->discount,
            ]);


            foreach ($carts as $cart) {
                $product_details = Product::find($cart->product_id);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'seller_id' => $cart->seller_id,
                    'product_details' => $product_details,
                    'qty' => $cart->quantity,
                    'price' => $cart->price,
                    'tax' => $cart->tax,
                    'tax_model' => $cart->tax_model,
                    'discount' => $cart->discount * $cart->quantity,
                    'delivery_status' => 'pending',
                    'payment_status' => 'unpaid',
                    'variant' => $cart->variant,
                    'variation' => $cart->variations,
                    'discount_type' => 'discount_on_product',
                    'is_stock_decreased' => $cart->quantity,
                ]);

                $variations = json_decode($product_details->variation, true);

                foreach ($variations as &$variation) {
                    if ($variation['type'] === $cart->variant) {
                        $variation['qty'] = max(0, $variation['qty'] - $cart->quantity);
                        break;
                    }
                }

                $product_details->variation = json_encode($variations);
                $product_details->current_stock = $product_details->current_stock - $cart->quantity;
                $product_details->save();
            }

            CartManager::cart_clean();

            return view(VIEW_FILE_NAMES['order_complete'], compact('order'));
        } elseif ($request->payment_method == 'bkash') {

            // Bkash Payment Gateway Code

            $inv = $invoice_id;
            $request['intent'] = 'sale';
            $request['mode'] = '0011'; //0011 for checkout
            $request['payerReference'] = $inv;
            $request['currency'] = 'BDT';
            $request['amount'] = $total_amount;
            $request['merchantInvoiceNumber'] = $inv;
            $request['callbackURL'] = config("bkash.callbackURL");

            $request_data_json = json_encode($request->all());

            $response =  BkashPaymentTokenize::cPayment($request_data_json);
            //$response =  BkashPaymentTokenize::cPayment($request_data_json,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..

            // dd($response); //if you are using sandbox and not submit info to bkash use it for 1 response

            if (isset($response['bkashURL'])) {
                BkashPaymentRecord::create([
                    'payment_id' => $response['paymentID'],
                    'customer_id' => $customer->id,
                    'cart_customer_id' => $request->customer_id,
                    'invoice_id' => $invoice_id,
                    'shipping_cost' => $shipping_cost,
                    'address' => $request->address,
                    'discount' => $request->discount,
                    'status' => 'pending'
                ]);
                return redirect()->away($response['bkashURL']);
            } else {

                return view(VIEW_FILE_NAMES['failed'], [
                    'message' => $response['statusMessage']
                ]);
            };
        } elseif ($request->payment_method == 'nagad') {

            // Nagad Payment Gateway Code
            $amount = '1000';
            $trx_id = $invoice_id;
            $response = NagadPayment::create($amount, $trx_id); // 1st parameter is amount and 2nd is unique invoice number

            if (isset($response) && $response->status == "Success") {
                return redirect()->away($response->callBackUrl);
            }
            return redirect()->back()->with("error-alert", "Invalid request try again after few time later");
        }
    }

    function filterPhoneNumber($phone)
    {
        // Remove any non-numeric characters (optional but safe)
        $phone = preg_replace('/\D/', '', $phone);

        // Take last 11 digits if more than 11
        return strlen($phone) > 11
            ? substr($phone, -11)
            : $phone;
    }

    public function bkash_callBack(Request $request)
    {

        if ($request->status == 'success') {
            $response = BkashPaymentTokenize::executePayment($request->paymentID);

            if (!$response) {
                $response = BkashPaymentTokenize::queryPayment($request->paymentID);
            }
            if (isset($response['statusCode']) && $response['statusCode'] != "0000") {
                return view(VIEW_FILE_NAMES['failed'], [
                    'message' => $response['statusMessage']
                ]);
            }

            if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {

                $payment = BkashPaymentRecord::where('payment_id', $response['paymentID'])->first();
                $order_id = 100000 + Order::all()->count() + 1;
                if (Order::find($order_id)) {
                    $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
                }

                $order = Order::create([
                    'id' => $order_id,
                    'invoice_id' => $payment->invoice_id,
                    'customer_id' => $payment->customer_id,
                    'payment_method' => 'bkash',
                    'payment_status' => 'paid',
                    'order_status' => 'pending',
                    'order_amount' => $response['amount'],
                    'shipping_cost' => $payment->shipping_cost,
                    'shipping_address' => $payment->address,
                    'order_type' => 'web',
                    'seller_id' => 0,
                    'discount_amount' => $payment->discount,
                ]);

                $carts = Cart::where('customer_id', $payment->cart_customer_id)->get();

                foreach ($carts as $cart) {
                    $product_details = Product::find($cart->product_id);

                    OrderDetail::create([
                        'order_id' => $order_id,
                        'product_id' => $cart->product_id,
                        'seller_id' => $cart->seller_id,
                        'product_details' => $product_details,
                        'qty' => $cart->quantity,
                        'price' => $cart->price,
                        'tax' => $cart->tax,
                        'tax_model' => $cart->tax_model,
                        'discount' => $cart->discount * $cart->quantity,
                        'delivery_status' => 'pending',
                        'payment_status' => 'paid',
                        'variant' => $cart->variant,
                        'variation' => $cart->variations,
                        'discount_type' => 'discount_on_product',
                        'is_stock_decreased' => $cart->quantity,
                    ]);

                    $variations = json_decode($product_details->variation, true);

                    foreach ($variations as &$variation) {
                        if ($variation['type'] === $cart->variant) {
                            $variation['qty'] = max(0, $variation['qty'] - $cart->quantity);
                            break;
                        }
                    }

                    $product_details->variation = json_encode($variations);
                    $product_details->current_stock = $product_details->current_stock - $cart->quantity;
                    $product_details->save();
                }

                BkashPaymentRecord::where('payment_id', $response['paymentID'])->update([
                    'status' => 'completed',
                    'trxID' => $response['trxID']
                ]);
                CartManager::cart_clean();

                return view(VIEW_FILE_NAMES['order_complete'], compact('order'));
            }
            return BkashPaymentTokenize::failure($response['statusMessage']);
        } else if ($request->status == 'cancel') {
            return view(VIEW_FILE_NAMES['failed'], [
                'message' => 'Your payment is canceled'
            ]);
        } else {
            return view(VIEW_FILE_NAMES['failed'], [
                'message' => 'Your transaction is failed'
            ]);
        }
    }

    public function nagad_callback(Request $request)
    {
        if (!$request->status && !$request->order_id) {
            return response()->json([
                "error" => "Not found any status"
            ], 500);
        }

        if (config("nagad.response_type") == "json") {
            return response()->json($request->all());
        }

        $verify = NagadPayment::verify($request->payment_ref_id); // $paymentRefId which you will find callback URL request parameter

        if (isset($verify->status) && $verify->status == "Success") {
            return $this->success($verify->orderId);
        } else {
            return $this->fail($verify->orderId);
        }
    }

    // invoice
    public function invoice($id)
    {
        $order = Order::find($id);
        return view('customer.invoice', compact('order'));
    }

    public function old_customer()
    {
        $phone = session('old_customer_phone');
        if (!$phone) {
            return redirect()->route('customer.login')->with('error', 'Phone number does not exist.');
        }
        $customer = User::where('phone', $phone)->first();
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Customer does not exist. Please try again.');
        }

        $rand_int = random_int(10000000, 99999999);
        $customer->update([
            'password' => bcrypt($rand_int),
            'from_others' => '0'
        ]);
        $message = "Your password is: {$rand_int} \nThank you. \nFrom: " . config('app.name');
        $this->sendSms($customer->phone, $message);

        session()->forget('old_customer_phone');

        return view('customer.old_customer',);
    }
}
