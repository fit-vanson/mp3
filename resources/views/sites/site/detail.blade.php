<div class="col-lg-3">

    <div class="card">
        <div class="card-body">

            <dl class="row mb-0">
                <dt class="col-sm-9"><h4 class="card-title">{{$site->site_name}}</h4></dt>
                <dt class="col-sm-3"><button type="button" data-id="{{$site->id}}" class="btn btn-info waves-effect waves-light getAIO">Get AIO</button></dt>
            </dl>

            <a target="_blank" href="//{{$site->site_web}}"><p class="card-title-desc">{{$site->site_web}}</p></a>
            <p style="text-align: center">
                <a class="image-popup-no-margins" href="{{$site->site_logo_url ? $site->site_logo_url :   URL::asset('/storage/sites/'.$site->id.'/'.$site->site_image) }}">
                    <img class="img-fluid" alt="" src="{{ $site->site_logo_url ? $site->site_logo_url : URL::asset('/storage/sites/'.$site->id.'/'.$site->site_image) }}" width="75">
                </a>
            </p>
            <dl class="row mb-0">
                <dt class="col-sm-3">Project</dt>
                <dd class="col-sm-9"><span class="badge badge-success">{{$site->site_project}}</span></dd>
                <dt class="col-sm-3">App name</dt>
                <dd class="col-sm-9">{{$site->site_app_name}}</dd>
                <dt class="col-sm-3">Categories</dt>
                <dd class="col-sm-9">{{count($site->categories)}}</dd>
                <dt class="col-sm-3">ADS</dt>
                <dd class="col-sm-9 site_adss">
                    @if($site->ad_switch ==1)
                        <a href="javascript:void(0)" data-id="{{$site->id}}" class="changeAds"><span class="badge badge-success">Active</span></a>
                    @else
                        <a href="javascript:void(0)" data-id="{{$site->id}}" class="changeAds"><span class="badge badge-danger">Deactivated</span></a>
                    @endif
                </dd>
                @if(isset($site->site_chplay_link))
                    <dt class="col-sm-3">Link CHPlay</dt>
                    <dd class="col-sm-9"><a target="_blank" href="{{$site->site_chplay_link}}"><p class="card-title-desc">Link</p></a></dd>
                @endif

                @if(isset($site->site_oppo_link))
                    <dt class="col-sm-3">Link Oppo</dt>
                    <dd class="col-sm-9"><a target="_blank" href="{{$site->site_oppo_link}}"><p class="card-title-desc">Link</p></a></dd>
                @endif

                @if(isset($site->site_vivo_link))
                    <dt class="col-sm-3">Link Vivo</dt>
                    <dd class="col-sm-9"><a target="_blank" href="{{$site->site_vivo_link}}"><p class="card-title-desc">Link</p></a></dd>
                @endif

                @if(isset($site->site_xiaomi_link))
                    <dt class="col-sm-3">Link Xiaomi</dt>
                    <dd class="col-sm-9"><a target="_blank" href="{{$site->site_xiaomi_link}}"><p class="card-title-desc">Link</p></a></dd>
                @endif

                @if(isset($site->site_huawei_link))
                    <dt class="col-sm-3">Link Huawei</dt>
                    <dd class="col-sm-9"><a target="_blank" href="{{$site->site_huawei_link}}"><p class="card-title-desc">Link</p></a></dd>
                @endif
            </dl>
        </div>
    </div>
</div>


