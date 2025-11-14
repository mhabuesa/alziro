<section class="most-visited-category section-gap pb-0 text-center">
    <div class="container">
        <div class="section-title-3 mb-0">
            <div class="mb-32px">
                <h2 class="title mx-auto mb-0 text-capitalize">{{ translate('most_visited_categories') }}</h2>
            </div>
        </div>

        <div class="most-visited-category-wrapper align-items-center">

            @if ($most_visited_categories[0])
                <a href="{{route('products',['id'=> $most_visited_categories[0]->id,'data_from'=>'category','page'=>1])}}"
                   class="most-visited-item">
                    <img loading="lazy" alt="{{ translate('category') }}"
                         src="{{ getValidImage(path: 'storage/app/public/category/'.($most_visited_categories[0]->icon), type:'category') }}">
                    <h4 class="title">{{ $most_visited_categories[0]->name }}</h4>
                    <div class="cont">
                        <h6 class="text-white font-semibold text-uppercase">{{ $most_visited_categories[0]->name }}</h6>
                        <span>{{ $most_visited_categories[0]->product_count }} {{ translate('product') }}</span>
                        <i class="bi bi-eye-fill"></i>
                    </div>
                </a>
            @endif

            <div class="most-visited-area">
                @foreach ($most_visited_categories as $key => $item)

                    @if ($key != 0 && $key < 8)
                        <a href="{{route('products',['id'=> $item->id,'data_from'=>'category','page'=>1])}}"
                           class="most-visited-item">
                            <img loading="lazy" alt="{{ translate('category') }}" src="{{ getValidImage(path: 'storage/app/public/category/'.$item->icon, type:'category') }}">
                            <h4 class="title">{{ $item->name }}</h4>
                            <div class="cont">
                                <h6 class="text-white font-semibold text-uppercase">{{ $item->name }}</h6>
                                <span>{{ $item->product_count }} {{ translate('product') }}</span>
                                <i class="bi bi-eye-fill"></i>
                            </div>
                        </a>
                    @endif

                @endforeach
            </div>

            @if (isset($most_visited_categories[8]) && $most_visited_categories[8])
                <a href="{{route('products',['id'=> $most_visited_categories[8]->id,'data_from'=>'category','page'=>1])}}"
                   class="most-visited-item">
                    <img loading="lazy" alt="{{ translate('category') }}"
                         src="{{ getValidImage(path: 'storage/app/public/category/'.$most_visited_categories[8]->icon, type:'category') }}">
                    <h4 class="title">{{ $most_visited_categories[8]->name }}</h4>
                    <div class="cont">
                        <h6 class="text-white font-semibold text-uppercase">{{ $most_visited_categories[8]->name }}</h6>
                        <span>{{ $most_visited_categories[8]->product_count }} {{ translate('product') }}</span>
                        <i class="bi bi-eye-fill"></i>
                    </div>
                </a>
            @endif
        </div>
    </div>
</section>
