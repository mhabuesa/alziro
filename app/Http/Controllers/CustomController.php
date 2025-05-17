<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Jobs\SendSmsJob;
use Illuminate\Http\Request;
use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;

class CustomController extends Controller
{
    public function AddNewCustomer(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'phone' => 'required|max_digits:11|min_digits:11',
            'address' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $data =  'saved-customer-' . $user->id;
            session()->put('current_user', $data);
            return back()->with('error', 'Phone number already exists!');
        }

        $password = '12345678';


        $customer = User::create([
            'name' => $request->name,
            'f_name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'street_address' => $request->address,
            'password' => bcrypt($password),
        ]);


        // Message
        $url = "http://bulksmsbd.net/api/smsapi";
        $apiKey = env('BULK_SMS_API_KEY');
        $senderId = env('BULK_SMS_SENDER_ID');
        $number = $request->phone;
        $message = "Welcome to Alziro! \n\nAccess your account at https://alziro.com/customer/login . \nEnter your phone number and Login Password: {$password}.";

        $data = [
            "api_key" => $apiKey,
            "senderid" => $senderId,
            "number" => $number,
            "message" => $message
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $data =  'saved-customer-' . $customer->id;
        session()->put('current_user', $data);
        return redirect()->back()->with('success', 'Customer added successfully.');
    }

    public function orderInfoUpdate(Request $request, $id)
    {
        $order_status = $request->order_status;
        $scheduled_date = $request->scheduled_date;
        $payment_status = $request->payment_status;
        $payment_method = $request->payment_method;

        $order = Order::find($id);
        $order->update([
            'order_status' => $order_status,
            'scheduled_date' => $scheduled_date,
            'payment_status' => $payment_status,
            'payment_method' => $payment_method
        ]);
        return redirect()->back()->with('success', 'Order info updated successfully.');
    }

    public function invoice($id)
    {

        $order = Order::find($id);
        return view('admin-views.pos.invoice', compact('order'));
    }

    public function steadfast_page($id)
    {

        $order = Order::find($id);
        return view('admin-views.order.steadfast', compact('order'));
    }

    public function transferToDelivery(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'amount' => 'required',
        ]);
        $order = Order::find($request->order_id);

        $name = $request->name;
        $phone = $request->phone;
        $address = $request->address;
        $amount = $request->amount;
        $note = $request->note ?? null;


        $orderData = [
            'invoice' => $order->invoice_id,
            'recipient_name' => $name,
            'recipient_phone' => $phone,
            'recipient_address' => $address,
            'cod_amount' => $amount,
            'note' => $note, // optional
        ];

        // Check required fields (exclude 'note')
        $requiredFields = collect($orderData)->except('note');

        if ($requiredFields->contains(null)) {
            return redirect()->back()->with('error', 'Order data is incomplete.');
        }


        $response = SteadfastCourier::placeOrder($orderData);
        if (!isset($response['status']) || $response['status'] != 200) {
            $errors = $response['errors'] ?? [];
            $errorMessages = collect($errors)->flatten()->implode(', ');

            return redirect()->back()->with('error', $errorMessages ?: 'Failed to place order.');
        }

        $order->update([
            'order_status' => 'out_for_delivery',
            'third_party_delivery_tracking_id' => $response['consignment']['tracking_code'],
            'third_party_delivery_consignment_id' => $response['consignment']['consignment_id'],
        ]);

        return redirect()->route('admin.orders.list', 'confirmed')->with('success', 'Order successfully transferred to Steadfast.');
    }

    public function orderDelete(Request $request)
    {
        $order = Order::find($request->id);
        $orderDetails = $order->details;
        foreach ($orderDetails as $key => $orderDetail) {
            $orderDetail->delete();
        }
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }

    public function smsMarketing()
    {

        $apiKey = env('BULK_SMS_API_KEY');
        $url = "https://bulksmsbd.net/api/getBalanceApi?api_key=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['balance'])) {
            $balance = $data['balance'];
            $sms = floor(($balance * 100) / 35);
        } else {
            $sms = 10;
        }


        $customers = User::where('id', '!=', 0)->get();
        return view('admin-views.sms-marketing.index', [
            'customers' => $customers,
            'sms' => $sms
        ]);
    }
    public function smsMarketing_send(Request $request)
    {

        $request->validate([
            'message' => 'required',
        ]);

        $query = User::query();
        // Filter by date
        if ($request->customer_selection == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->customer_selection == '15_days') {
            $query->whereBetween('created_at', [now()->subDays(15), now()]);
        } elseif ($request->customer_selection == '30_days') {
            $query->whereBetween('created_at', [now()->subDays(30), now()]);
        } elseif ($request->customer_selection == 'custom') {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        // Filter by order status
        if ($request->filter_customer == 'ordered') {
            $query->whereHas('orders');
        } elseif ($request->filter_customer == 'not_ordered') {
            $query->whereDoesntHave('orders');
        }

        // Filter by order status
        if ($request->filter == 'ordered') {
            $query->whereHas('orders');
        } elseif ($request->filter == 'not_ordered') {
            $query->whereDoesntHave('orders');
        }

        $customers = $query->where('id', '!=', 0)->whereRaw('LENGTH(phone) = 11')->get();
        foreach ($customers as $customer) {
            dispatch(new SendSmsJob($customer->phone, $request->message))->delay(now()->addSeconds(2));
        }

        return redirect()->back()->with('success', 'SMS sending started.');
    }

    public function CustomerCount(Request $request)
    {
        $query = User::query();

        // Filter by date
        if ($request->selection == 'week') {
            $query->where('created_at', '>=', now()->subWeek());
        } elseif ($request->selection == '15days') {
            $query->where('created_at', '>=', now()->subDays(15));
        } elseif ($request->selection == 'month') {
            $query->where('created_at', '>=', now()->subMonth());
        } elseif ($request->selection == 'custom') {
            if ($request->from && $request->to) {
                $query->whereBetween('created_at', [$request->from, $request->to]);
            }
        }

        // Filter by order status
        if ($request->filter == 'ordered') {
            $query->whereHas('orders');
        } elseif ($request->filter == 'not_ordered') {
            $query->whereDoesntHave('orders');
        }

        $count = $query->count();
        $names = $query->pluck('name')->toArray();

        return response()->json(['count' => $count, 'names' => $names]);
    }


    public function multipleInvoice(Request $request)
    {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'required|exists:orders,id',
        ]);
        $order = Order::whereIn('id', $request->id) // e.g., array of IDs
            ->get();

        return view('admin-views.order.multiple_invoice', compact('order'));
    }

    public function customer_update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'address' => 'required',
        ]);

        $customer = User::find($id);
        $customer->name = $request->name;
        $customer->street_address = $request->address;
        $customer->save();
        return back()->with('success', 'Customer updated successfully!');
    }

    public function customer_import_form()
    {

        return view('admin-views.customer.customer_import');
    }

    public function customer_import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new CustomerImport, $request->file('file'));

        return back()->with('success', 'Customers imported successfully!');
    }

    public function edit($id)
    {
        $customer = User::find($id);
        return view('admin-views.customer.edit', [
            'customer' => $customer,
        ]);
    }
    public function customer_update_info(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $id . ',id',
            'email' => 'nullable|unique:users,email,' . $id . ',id',
            'address' => 'required',
        ]);

        $customer = User::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->street_address = $request->address;
        $customer->save();
        return back()->with('success', 'Customer updated successfully!');
    }
}
