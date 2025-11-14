<section class="py-4">
    <div class="container">

        @if (!empty($web_config['features_section']['features_section_top']))
            <div class="section-title text-center pt-xl-2">
                <h2 class="title">{{ json_decode($web_config['features_section']['features_section_top'])->title }}</h2>
                <p>
                    {{ json_decode($web_config['features_section']['features_section_top'])->subtitle }}
                </p>
            </div>
        @endif

        @if (!empty($web_config['features_section']['features_section_middle']))
            <div class="table-responsive">
                <div class="how-we-work-grid">
                    @php($totalFeatures = count(json_decode($web_config['features_section']['features_section_middle'])))
                    @foreach (json_decode($web_config['features_section']['features_section_middle']) as $key=> $item)
                        <div class="how-to-card max-width-unset-custom">
                            <div class="how-to-icon">
                                {{ ($key+1 <10 ? '0'.$key+1:$key+1) }}
                            </div>
                            <div class="how-to-cont">
                                <h5 class="title">{{ $item->title }}</h5>
                                <div>{{ $item->subtitle }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@if(!empty($web_config['features_section']['features_section_bottom']))
    <div class="support-section">
        <div class="container">
            <div class="support-wrapper">
                @foreach(json_decode($web_config['features_section']['features_section_bottom']) as $item)
                    <div class="support-card mb-4">
                        <div class="support-card-inner">
                            <div class="icon">
                                <img loading="lazy" src="{{ getValidImage(path: 'storage/app/public/banner/'.$item->icon, type:'banner') }}"
                                     alt="{{ translate('banner') }}" class="icon">
                            </div>
                            <h6 class="title">{{ $item->title }}</h6>
                            <div>{{ $item->subtitle }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
