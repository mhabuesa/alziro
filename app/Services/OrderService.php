<?php

namespace App\Services;

class OrderService
{
    public function __construct()
    {
    }
    public function getPOSOrderData(int|string $orderId, string $advanced, string $payment_status,string $invoice_id,array $cart,float $amount,string $paymentType ,string $addedBy, string $shippingCost,int $userId):array
    {
        return [
            'id' => $orderId,
            'invoice_id' => $invoice_id,
            'customer_id' => $userId,
            'customer_type' => 'customer',
            'payment_status' => $payment_status,
            'advanced' => $advanced,
            'order_status' => 'pending',
            'seller_id' => $addedBy =='seller' ? auth('seller')->id() : auth('admin')->id(),
            'seller_is' => $addedBy,
            'payment_method' => $paymentType,
            'order_type' => 'POS',
            'checked' =>1,
            'extra_discount' =>$cart['ext_discount'] ?? 0,
            'extra_discount_type' => $cart['ext_discount_type'] ?? null,
            'order_amount' => currencyConverter($amount),
            'discount_amount' => $cart['coupon_discount'] ?? 0,
            'coupon_code' => $cart['coupon_code']??null,
            'discount_type' => (isset($cart['coupon_code']) && $cart['coupon_code']) ? 'coupon_discount' : NULL,
            'coupon_discount_bearer' => $cart['coupon_bearer'] ?? 'inhouse',
            'shipping_cost' => $shippingCost,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
