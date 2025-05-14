<ul class="list-group list-group-flush border-0">
    @foreach($products as $product)
        <li class="list-group-item border-0">
            <form action="{{ route('product-compare.index') }}" method="post">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <input type="hidden" name="compare_id" value="{{ $compare_id }}">
                <button class="text-base btn text-start p-0 m-0 border-0" type="submit">
                    {{$product['name']}}
                </button>
            </form>
        </li>
    @endforeach
</ul>
