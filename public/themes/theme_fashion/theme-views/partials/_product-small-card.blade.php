<div class="similer-product-item">
    <div class="img">
        <a href="{{route('product',$product->slug)}}">
            <img loading="lazy" alt="{{ translate('products') }}"
                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
        </a>
        <a href="javascript:" class="wish-icon p-2 addWishlist_function_view_page" data-id="{{$product['id']}}">
            @php($wishlist = count($product->wishList)>0 ? 1 : 0)
            <i class="{{($wishlist == 1?'bi-heart-fill text-danger':'bi-heart')}}  wishlist_{{$product['id']}}"></i>
        </a>
    </div>
    <div class="cont thisIsALinkElement" data-linkpath="{{route('product',$product->slug)}}">
        <h6 class="title">
            <a href="{{route('product',$product->slug)}}"
               title="{{ $product['name'] }}">{{ Str::limit($product['name'], 18) }}</a>
        </h6>
        <strong class="text-text-2">
            {{\App\Utils\Helpers::currency_converter(
                $product->unit_price-(\App\Utils\Helpers::get_product_discount($product,$product->unit_price))
            )}}
        </strong>
    </div>
</div>
