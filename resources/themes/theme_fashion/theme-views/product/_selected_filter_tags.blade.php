@if ($tags_category != null || $tags_brands != null || $rating != null || (isset($sort_by) && $sort_by != null && $sort_by != 'default'))
<div class="selected-filters custom-selected-filters">
    <ul>

        @isset($sort_by)
            @if ($sort_by != 'default')
            <li class="remove_tags_sortBy">
                <span>{{ucwords(translate($sort_by))}}</span> <i class="bi bi-x-lg"></i>
            </li>
            @endif
        @endisset

        @isset($tags_category)
            @foreach ($tags_category as $item)
            <li class="remove_tags_Category" data-id="{{ $item->id }}">
                <span>{{$item->name}}</span> <i class="bi bi-x-lg"></i>
            </li>
            @endforeach
        @endisset

        @isset($tags_brands)
            @foreach ($tags_brands as $item)
            <li class="remove_tags_Brand" data-id="{{ $item->id }}">
                <span>{{$item->name}}</span> <i class="bi bi-x-lg"></i>
            </li>
            @endforeach
        @endisset

        @isset($rating)
            @foreach ($rating as $item)
            <li class="remove_tags_review" data-id="{{ $item }}">
                <i class="bi bi-star-fill"></i>
                <span>{{$item}}</span> <i class="bi bi-x-lg"></i>
            </li>
            @endforeach
        @endisset

    </ul>
    @if (request('data_from') == 'category' || request('data_from') == 'brand')
        <a href="{{route('products')}}" class="clear-all text-capitalize">
            {{ translate('clear_all') }}
        </a>
    @else
        <div class="clear-all text-capitalize fashion_products_list_form_reset">
            {{ translate('clear_all') }}
        </div>
    @endif
</div>
@endif
