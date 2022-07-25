<div class="col-lg-3">

    <div class="card">
        <div class="card-body">



            <h4 class="card-title">{{$site->site_name}}</h4>
            <a target="_blank" href="//{{$site->site_web}}"><p class="card-title-desc">{{$site->site_web}}</p></a>
            <p style="text-align: center">
                <a class="image-popup-no-margins" href="{{ URL::asset('/storage/sites/'.$site->site_image) }}">
                    <img class="img-fluid" alt="" src="{{ URL::asset('/storage/sites/'.$site->site_image) }}" width="75">
                </a>
            </p>
            <dl class="row mb-0">
                <dt class="col-sm-3">Categories</dt>
                <dd class="col-sm-9">{{count($site->categories)}}</dd>

{{--                <dt class="col-sm-3">Feature Images</dt>--}}
{{--                <dd class="col-sm-9 FeatureImagesNum">{{isset($site->site_feature_images)? count(json_decode($site->site_feature_images)): 0}}</dd>--}}

                <dt class="col-sm-3">ADS</dt>
                <dd class="col-sm-9 site_adss">
                    @if($site->ad_switch ==1)
                        <a href="javascript:void(0)" data-id="{{$site->id}}" class="changeAds"><span class="badge badge-success">Active</span></a>
                    @else
                        <a href="javascript:void(0)" data-id="{{$site->id}}" class="changeAds"><span class="badge badge-danger">Deactivated</span></a>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
</div>


