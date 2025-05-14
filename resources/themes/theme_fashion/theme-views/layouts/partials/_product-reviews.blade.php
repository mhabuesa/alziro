@foreach ($productReviews as $item)
<li>
    <div class="author-area">
        <img loading="lazy" alt="{{ translate('profile') }}" class="mx-1"
             src="{{ getValidImage(path: "storage/app/public/profile/".(isset($item->user)?$item->user->image:''), type: 'avatar') }}">
        <div class="cont">
            <h6>
                @if($item->user)
                    <div href="javascript:" class="text-capitalize">{{$item->user->f_name}} {{$item->user->l_name}}</div>
                @else
                    <a href="javascript:" class="text-capitalize">{{translate('user_not_exist')}}</a>
                @endif
            </h6>
            <span>
                <i class="bi bi-star-fill text-star"></i>
                {{$item->rating}}/5
            </span>
        </div>
    </div>

    <div class="content-area max-height-fixed review-comment-id{{ $item['id'] }}">
        <p class="mb-3 mx-3 review-comment-id{{ $item['id'] }}-primary">
            @if(mb_strlen(strip_tags(str_replace('&nbsp;', ' ', $item->comment))) > 450)
                {{ Str::limit((strip_tags(str_replace('&nbsp;', ' ', $item->comment))), 450) }}
                <span class="read-more-current-review cursor-pointer text-base" data-element=".review-comment-id{{ $item['id'] }}">{{ translate('read_more') }}</span>
            @else
                {!! $item->comment !!}
            @endif
        </p>

        <p class="mb-3 mx-3 review-comment-id{{ $item['id'] }}-hidden d--none">
            {!! $item->comment !!}
        </p>

        @if(isset($item->attachment) && !empty(json_decode($item->attachment)))
        <div class="products-comments-img d-flex flex-wrap gap-2">
            @foreach (json_decode($item->attachment) as $img)
                <a href="{{ getValidImage(path: 'storage/app/public/review/'.$img, type: 'product') }}" class="custom-image-popup">
                    <img loading="lazy" src="{{ getValidImage(path: 'storage/app/public/review/'.$img, type: 'product') }}" alt="{{$item->name}}">
                </a>
            @endforeach
        </div>
        @endif

    </div>
</li>
@endforeach
