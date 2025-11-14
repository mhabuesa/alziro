@php($cart_mobile=\App\Utils\CartManager::get_cart())
<span class="icon">
    <i class="bi bi-bag-check-fill"></i>
    <span class="status">{{$cart_mobile->count()}}</span>
</span>
<div>
    @if($cart_mobile->count() > 0)
        @php($sub_total=0)
        @php($total_tax=0)
        @foreach($cart_mobile as  $cartItem)
            @php($sub_total+=($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])
            @php($total_tax+=$cartItem['tax']*(int)$cartItem['quantity'])
        @endforeach
        @if ($sub_total > 1000000)
            {{\App\Utils\Helpers::currency_converter($sub_total / 1000000)}}M+
        @elseif($sub_total > 1000)
            {{\App\Utils\Helpers::currency_converter($sub_total / 1000)}}K+
        @else
            {{\App\Utils\Helpers::currency_converter($sub_total)}}
        @endif
    @endif
</div>
