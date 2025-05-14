<ul class="list-group list-group-flush border-0">
    @foreach($products as $product)
        <li class="list-group-item border-0">
            <a href="{{route('product',$product->slug)}}" class="text-base">
                {{$product['name']}}
            </a>
        </li>
    @endforeach

    @isset($seller_products)
        @foreach($seller_products as $product)
            <li class="list-group-item border-0">
                <a href="{{route('product',$product->slug)}}" class="text-base">
                    {{$product['name']}}
                </a>
            </li>
        @endforeach
    @endisset
</ul>
