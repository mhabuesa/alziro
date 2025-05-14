<form class="search-form" action="{{route('products')}}" type="submit">
    <div class="input-group search_input_group">
        <select class="select2-init" id="search_category_value_web" name="search_category_value">
            <option value="all">{{translate('all_Categories')}}</option>
            @foreach($web_config['main_categories'] as $category)
            <option value="{{ $category->id }}" {{ $category->id == request('search_category_value') ? 'selected':'' }}>{{$category['name']}}</option>
            @endforeach
        </select>
        <input type="text" class="form-control" id="input-value-web" name="name" value="{{ request('name') }}"
                placeholder="{{ translate('search_for_items_or_store') }}">

        <button class="btn btn-base" type="submit"><i class="bi bi-search"></i></button>
        <div class="card search-card position-absolute z-99 w-100 bg-white d-none top-100 start-0 search-result-box-web"></div>
    </div>
    <input name="data_from" value="search" hidden>
    <input name="page" value="1" hidden>
</form>
