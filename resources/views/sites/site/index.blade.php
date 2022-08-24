@extends('layouts.master')

@section('title') {{$page_title}} @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>


@endsection

@section('content')
    <!-- start page title -->
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        @include('sites.site.detail')

        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#home-1" role="tab">
                                <span class="d-block d-sm-block"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Home</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#feature-images-1" role="tab">
                                <span class="d-block d-sm-block"><i class="far fa-image"></i></span>
                                <span class="d-none d-sm-block">Feature images</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#adss-1" role="tab">
                                <span class="d-block d-sm-block"><i class="fas fa-ad"></i></span>
                                <span class="d-none d-sm-block">Ads</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-toggle="tab" href="#categories-1" role="tab">
                                <span class="d-block d-sm-block"><i class="far fa-folder-open"></i></span>
                                <span class="d-none d-sm-block">Categories</span>
                            </a>
                        </li>

                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#listips-1" role="tab">
                                <span class="d-block d-sm-block"><i class="fas fa-clipboard"></i></span>
                                <span class="d-none d-sm-block">List IP</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane p-3" id="home-1" role="tabpanel">
                            <form id="form{{preg_replace('/\s+/','',$page_title)}}_home">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="{{$site->id}}">

                                    <div class="form-group col-lg-12">
                                        <label>Header Title</label>
                                        <input type="text" class="form-control" id="site_header_title" name="site_header_title" value="{{$site->site_header_title}}" >
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>Header Content</label>
                                        <textarea  class="form-control" id="site_header_content" name="site_header_content"  rows="5" >{{$site->site_header_content}}</textarea>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>Body Title</label>
                                        <input type="text" class="form-control" id="site_body_title" name="site_body_title" value="{{$site->site_body_title}}" >
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>Body Content</label>
                                        <textarea  class="form-control" id="site_body_content" name="site_body_content"  rows="5" >{{$site->site_body_content}}</textarea>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>Footer Title</label>
                                        <input type="text" class="form-control" id="site_footer_title" name="site_footer_title" value="{{$site->site_footer_title}}" >
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>Footer Content</label>
                                        <textarea  class="form-control" id="site_footer_content" name="site_footer_content"  rows="5" >{{$site->site_footer_content}}</textarea>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Direct Link</label>
                                        <input type="text" class="form-control" id="site_direct_link" name="site_direct_link" value="{{$site->site_direct_link}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Link CHPlay</label>
                                        <input type="text" class="form-control" id="site_chplay_link" name="site_chplay_link" value="{{$site->site_chplay_link}}" >
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label>Policy</label>
                                        <textarea  class="form-control" id="site_policy" name="site_policy"  rows="5" >{{$site->site_policy}}</textarea>
                                    </div>

                                    <div class="form-group mb-0">
                                        <div>
                                            <button type="submit" id="saveBtn{{preg_replace('/\s+/','',$page_title)}}" class="btn btn-primary waves-effect waves-light mr-1">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane p-3" id="feature-images-1" role="tabpanel">

                            <div class="form-group">

                                <form id="form{{preg_replace('/\s+/','',$page_title)}}_load_view_by">

                                    <input type="hidden" name="id" id="id_load_view_by" value="{{$site->id}}">

                                    <label class="d-block mb-3">Load Feature Image</label>
                                    <?php
                                        $loads = ['Random','Manual','Most View','Feature Wallpaper'];
                                        foreach ($loads as $key=>$load){
                                    ?>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"  id="load_feature_{{$key}}" name="load_view_by" class="custom-control-input" value="{{$key}}" {{$site->load_view_by == $key ? 'checked' : ''}} >
                                            <label class="custom-control-label" for="load_feature_{{$key}}">{{$load}}</label>
                                        </div>

                                    <?php
                                    }
                                    ?>
                                    <hr>

                                    <label class="d-block mb-3">Load Categories</label>
                                    <?php
                                    $loads = ['Random','Most View','Update New'];
                                    foreach ($loads as $key=>$load){
                                    ?>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"  id="load_categories_{{$key}}" name="load_categories" class="custom-control-input" value="{{$key}}" {{$site->load_categories == $key ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="load_categories_{{$key}}">{{$load}}</label>
                                    </div>

                                    <?php
                                    }
                                    ?>

                                    <hr>

                                    <label class="d-block mb-3">Load Wallpaper By Category</label>
                                    <?php
                                    $loads = ['Random','Most Like','Most View','Update New'];
                                    foreach ($loads as $key=>$load){
                                    ?>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"  id="load_wallpapers_category_{{$key}}" name="load_wallpapers_category" class="custom-control-input" value="{{$key}}" {{$site->load_wallpapers_category == $key ? 'checked' : ''}} >
                                        <label class="custom-control-label" for="load_wallpapers_category_{{$key}}">{{$load}}</label>
                                    </div>

                                    <?php
                                    }
                                    ?>

                                </form>
                            </div>

                            <div class="button-items">
                                <button type="button" class="btn btn-success waves-effect waves-light updateFeatureImages">Update</button>
                            </div>
                            <br>
                            <div class="zoom-gallery FeatureImages ">
                                @if($site->site_feature_images)
                                    @foreach(json_decode($site->site_feature_images,true) as $feature_image)
                                        <a class="float-left" href="{{asset('storage/sites/'.$site->id.'/featureimages/'.$feature_image.'')}}" title="{{$site->site_web}}">
                                            <img src="{{asset('storage/sites/'.$site->id.'/featureimages/'.$feature_image.'')}}" alt="" width="150">
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane p-3" id="adss-1" role="tabpanel">
                            <form id="form{{preg_replace('/\s+/','',$page_title)}}_ads">
                                <input type="hidden" name="id" id="id_ads" value="{{$site->id}}">

                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>Ads Provider</label>
                                        <select class="select2 form-control" style="width: 100%"  id="ads_provider" name="ads_provider">
                                            <option value="ADMOB">ADMOB</option>
                                            <option value="FACEBOOKBIDDING">FACEBOOKBIDDING</option>
                                            <option value="APPLOVIN">APPLOVIN</option>
                                            <option value="IRONSOURCE">IRONSOURCE</option>
                                            <option value="STARTAPP">STARTAPP</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <h4 class="font-size-18">ADMOB</h4>
                                    </div>

                                    <?php $ads = json_decode($site->site_ads,true);?>


                                    <div class="form-group col-lg-6">
                                        <label>Publisher ID</label>
                                        <input type="text" class="form-control" id="AdMob_Publisher_ID" name="AdMob_Publisher_ID" value="{{@$ads['AdMob_Publisher_ID']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>App ID</label>
                                        <input type="text" class="form-control" id="AdMob_App_ID" name="AdMob_App_ID" value="{{@$ads['AdMob_App_ID']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Banner Ad Unit ID</label>
                                        <input type="text" class="form-control" id="AdMob_Banner_Ad_Unit_ID" name="AdMob_Banner_Ad_Unit_ID" value="{{@$ads['AdMob_Banner_Ad_Unit_ID']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Interstitial Ad Unit ID</label>
                                        <input type="text" class="form-control" id="AdMob_Interstitial_Ad_Unit_ID" name="AdMob_Interstitial_Ad_Unit_ID" value="{{@$ads['AdMob_Interstitial_Ad_Unit_ID']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Reward Ad Unit ID</label>
                                        <input type="text" class="form-control" id="AdMob_App_Reward_Ad_Unit_ID" name="AdMob_App_Reward_Ad_Unit_ID" value="{{@$ads['AdMob_App_Reward_Ad_Unit_ID']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Native Ad Unit ID</label>
                                        <input type="text" class="form-control" id="AdMob_Native_Ad_Unit_ID" name="AdMob_Native_Ad_Unit_ID" value="{{@$ads['AdMob_Native_Ad_Unit_ID']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Open Ad Unit ID</label>
                                        <input type="text" class="form-control" id="AdMob_App_Open_Ad_Unit_ID" name="AdMob_App_Open_Ad_Unit_ID" value="{{@$ads['AdMob_App_Open_Ad_Unit_ID']}}" >
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <h4 class="font-size-18">APPLOVIN</h4>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Applovin Banner</label>
                                        <input type="text" class="form-control" id="applovin_banner" name="applovin_banner" value="{{@$ads['applovin_banner']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Applovin Interstitial</label>
                                        <input type="text" class="form-control" id="applovin_interstitial" name="applovin_interstitial" value="{{@$ads['applovin_interstitial']}}" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Applovin Reward</label>
                                        <input type="text" class="form-control" id="applovin_reward" name="applovin_reward" value="{{@$ads['applovin_reward']}}" >
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <h4 class="font-size-18">IRONSOURCE</h4>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Ironsource Id</label>
                                        <input type="text" class="form-control" id="ironsource_id" name="ironsource_id" value="{{@$ads['ironsource_id']}}" >
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <h4 class="font-size-18">STARTAPP</h4>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Startapp Id</label>
                                        <input type="text" class="form-control" id="startapp_id" name="startapp_id" value="{{@$ads['startapp_id']}}" >
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <div>
                                        <button type="submit" id="saveBtn{{preg_replace('/\s+/','',$page_title)}}" class="btn btn-primary waves-effect waves-light mr-1">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane active p-3" id="categories-1" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive">
                                                    <table id="table{{preg_replace('/\s+/','',$page_title)}}_categories" class="table table-bordered dt-responsive"
                                                           style="width: 100%;">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 30%">Image</th>
                                                            <th style="width: 20%">Name</th>
                                                            <th style="width: 10%">Real</th>
                                                            <th style="width: 20%">Tags</th>
                                                            <th style="width: 5%">Music Count</th>
                                                            <th style="width: 10%">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div>
                        <div class="tab-pane p-3" id="listips-1" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive">
                                                    <table id="table{{preg_replace('/\s+/','',$page_title)}}_listips" class="table table-bordered dt-responsive"
                                                           style="width: 100%;">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 40%">IP Address</th>
                                                            <th style="width: 30%">Count</th>
                                                            <th style="width: 10%">Update At</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modalFeatureImages" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="FeatureImagesModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('sites.update_FeatureImages')}}" enctype="multipart/form-data"
                          class="dropzone" id="form{{preg_replace('/\s+/','',$page_title)}}_FeatureImages">
                        @csrf
                        <input type="hidden" name="id" id="id_FeatureImages" value="{{$site->id}}">
                        <div class="fallback">
                            <input name="file" type="file" multiple="multiple">
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="modalSiteCategory" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="SiteCategoryModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="card-body">
                    <form id="form{{preg_replace('/\s+/','',$page_title)}}Category">

                        <input type="hidden" name="site_id" id="site_id" value="{{$site->id}}">
                        <input type="hidden" name="category_id" id="category_id">
                        <input  id="image" type="file" name="image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                        <img id="avatar" width="200px" src="{{asset('assets/images/1.png')}}">

                        <div class="form-group">
                            <label>Category name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Tags Select</label>
                            <select class="select2 form-control select2-multiple" id="select_tags"
                                    name="select_tags[]" multiple="multiple"
                                    data-placeholder="Choose ..." style="width: 100%">
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Order</label>
                            <input type="text" class="form-control" id="category_order" name="category_order" >
                        </div>

                        <div class="form-group">
                            <label>View Count</label>
                            <input type="text" class="form-control" id="category_view_count" name="category_view_count" >
                        </div>

                        <div class="form-group">
                            <input type="checkbox" id="category_checked_ip" name="category_checked_ip"  switch="none" checked="">
                            <label for="category_checked_ip" data-on-label="Real" data-off-label="Fake"></label>
                        </div>

                        <div class="form-group">
                                <button type="submit" id="saveBtn{{preg_replace('/\s+/','',$page_title)}}Category" class="btn btn-primary waves-effect waves-light mr-1">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect">Cancel</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->





@endsection

@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

    <!--tinymce js-->
    <script src="{{ URL::asset('/assets/libs/tinymce/tinymce.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>

    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            $('#avatar').click(function(){
                $('#image').click();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.changeAds', function (data) {
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/change_ajax") }}/"+id+"?action=ads",
                    success: function (data) {
                        $(".site_adss").load(" .site_adss");
                        toastr['success']('', data.success, {
                            showMethod: 'fadeIn',
                            hideMethod: 'fadeOut',
                            timeOut: 1000,
                        });
                    },
                    error: function (data) {
                    }
                });
            });

            $(document).on('click', '.getAIO', function (data) {
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/get-aio") }}/"+id,
                    success: function (data) {
                        if(data.success){
                            location.reload();
                            toastr['success']('', data.success, {
                                showMethod: 'fadeIn',
                                hideMethod: 'fadeOut',
                                timeOut: 1000,
                            });
                        }
                        if(data.error){
                            toastr['error']('', data.error, {
                                showMethod: 'fadeIn',
                                hideMethod: 'fadeOut',
                                timeOut: 1000,
                            });
                        }

                    },
                    error: function (data) {
                    }
                });
            });

            if ($("#site_policy").length > 0) {
                tinymce.init({
                    menubar:false,
                    selector: "textarea#site_policy",
                    height: 500,
                    plugins: ["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker", "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking", "save table contextmenu directionality emoticons template paste textcolor"],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

                });
            }


            $('#form{{preg_replace('/\s+/','',$page_title)}}_home').on('submit', function (event) {
                event.preventDefault();
                // var id = $('#id').val();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}_home")[0]);

                $.ajax({
                    data: formData,
                    url: '{{route('sites.update_site')}}',
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            toastr['success'](data.success, 'Success!');

                        }
                        if (data.errors) {
                            for (var count = 0; count < data.errors.length; count++) {
                                toastr['error'](data.errors[count], 'Error!',);
                            }
                        }
                    }
                });


            });

            $('#form{{preg_replace('/\s+/','',$page_title)}}_ads').on('submit', function (event) {
                event.preventDefault();
                var id = $('#id').val();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}_ads")[0]);

                $.ajax({
                    data: formData,
                    url: '{{route('sites.update_ads')}}',
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            toastr['success'](data.success, 'Success!');

                        }
                        if (data.errors) {
                            for (var count = 0; count < data.errors.length; count++) {
                                toastr['error'](data.errors[count], 'Error!',);
                            }
                        }
                    }
                });


            });

            $('.updateFeatureImages').click(function () {
                $('#modalFeatureImages').modal('show');
            });
            $('.createSite').hide()


            $('#form{{preg_replace('/\s+/','',$page_title)}}_FeatureImages').dropzone(
                {
                    maxFilesize: 20,
                    parallelUploads: 20,
                    uploadMultiple: true,
                    acceptedFiles: ".jpeg,.jpg,.png,.gif",
                    addRemoveLinks: true,
                    timeout: 0,
                    dictRemoveFile: 'Xoá',
                    init: function () {
                        var _this = this; // For the closure
                        this.on('success', function (file, response) {
                            if (response.success) {
                                _this.removeFile(file);
                                toastr['success'](file.name, response.success, {
                                    showMethod: 'slideDown',
                                    hideMethod: 'slideUp',
                                    timeOut: 1000,
                                });
                            }
                            if (response.errors) {
                                for (var count = 0; count < response.errors.length; count++) {
                                    toastr['error'](file.name, response.errors[count], {
                                        showMethod: 'slideDown',
                                        hideMethod: 'slideUp',
                                        timeOut: 5000,
                                    });
                                }
                            }
                            $('#modalFeatureImages').modal('hide');
                            $(".FeatureImages").load(" .FeatureImages");
                            $(".FeatureImagesNum").load(" .FeatureImagesNum");
                        });
                    },
                });

            var button = $('input[type=radio]')

            button.change(function() {
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}_load_view_by")[0]);
                $.ajax({
                    data: formData,
                    url: '{{route('sites.update_site')}}',
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            toastr['success'](data.success, 'Success!');
                        }
                        if (data.errors) {
                            for (var count = 0; count < data.errors.length; count++) {
                                toastr['error'](data.errors[count], 'Error!',);
                            }
                        }
                    }
                });
            });




            var dtTable = $('#table{{preg_replace('/\s+/','',$page_title)}}_categories').DataTable({
                processing: true,
                serverSide: true,
                displayLength: 50,
                ajax: {
                    url: "{{route('sites.getIndexCategories')}}",
                    type: "post",
                    data: {
                        "id": "{{ request()->id }}"
                    }
                },
                columns: [
                    // columns according to JSON
                    { data: 'category_image',className: "align-middle text-center " },
                    { data: 'category_name',  className: "align-middle", },
                    { data: 'category_checked_ip',className: "align-middle",},
                    { data: 'tags',className: "align-middle", orderable: false},
                    { data: 'music_count',className: "align-middle"},

                    { data: 'action',className: "align-middle text-center ", orderable: false }
                ],
                dom:
                    '<"d-flex justify-content-between mx-2 row mt-75"' +
                    '<" col-sm-12 col-lg-2 d-flex justify-content-center justify-content-lg-start" l>' +
                    // '<"button-items"B>'+
                    '<"col-sm-12 col-lg-4 ps-xl-75 ps-0"<" d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f><"col-sm-12 col-md-3"B>>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-3"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',

                columnDefs: [
                    {
                        targets: 3,
                        responsivePriority: 1,
                        render: function (data) {
                            var tags = data,
                                $output = '';
                            var stateNum = Math.floor(Math.random() * 6) + 1;
                            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                            var $state = states[stateNum];
                            $.each(tags, function(i, item) {
                                $output += '<a href="{{route("musics.index")}}?search='+item.tag_name+'"> <span class="badge badge-'+$state+'" style="font-size: 100%">' + item.tag_name + '</span> </a> ';
                                // $output += '11111111 ';
                                return i<2;
                            });
                            if(tags.length > 3){
                                $output += ' <span class="badge badge-'+$state+'" style="font-size: 100%"> ...</span> '
                            }
                            return $output
                        }
                    },
                ],
                buttons: [
                    {
                        text: 'Create',
                        className: 'createSiteCategory btn btn-success',
                        attr: {
                            'type': 'submit'
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],

                order: [1, 'asc'],

                fnDrawCallback: function () {
                    $('.image-popup-no-margins').magnificPopup({
                        type: 'image',
                        closeOnContentClick: true,
                        closeBtnInside: false,
                        fixedContentPos: true,
                        mainClass: 'mfp-no-margins mfp-with-zoom',
                        // class to remove default margin from left and right side
                        image: {
                            verticalFit: true
                        },
                        zoom: {
                            enabled: true,
                            duration: 300 // don't foget to change the duration also in CSS

                        }
                    });
                }

            });

            $('.create{{preg_replace('/\s+/','',$page_title)}}Category').click(function () {
                $('#form{{preg_replace('/\s+/','',$page_title)}}Category').trigger("reset");
                $('#modal{{preg_replace('/\s+/','',$page_title)}}Category').modal('show');
                $('#{{preg_replace('/\s+/','',$page_title)}}CategoryModalLabel').html("Add {{$page_title}} Category");
                $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}Category').val("create");

                $('#id').val('');
                $('#category_view_count').val(Math.floor(Math.random() * 1000) + 1000);
                $('#category_order').val(Math.floor(Math.random() * 2));

                $(".select2").select2({
                    closeOnSelect: false,
                });
            });


            $(document).on('click','.editSiteCategory', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}Category').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/categories/edit") }}/"+id,
                    success: function (data) {
                        $('#modal{{preg_replace('/\s+/','',$page_title)}}Category').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}CategoryModalLabel').html("Edit Category {{$page_title}}: "+ data.categories.category_name);
                        $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}Category').val("update");
                        $('#form{{preg_replace('/\s+/','',$page_title)}}Category').trigger("reset");

                        $('#category_id').val(data.categories.id);
                        $('#site_id').val({{ request()->id }});
                        $('#category_name').val(data.categories.category_name);
                        $('#category_order').val(data.categories.category_order);
                        $('#category_view_count').val(data.categories.category_view_count);
                        if (data.categories.category_checked_ip == 0) {
                            $('#category_checked_ip').prop('checked', true);
                        } else {
                            $('#category_checked_ip').prop('checked', false);
                        }
                        var id_cate =[];
                        $.each(data.categories.tags, function(i, item) {
                            id_cate.push(item.id.toString())
                        });
                        $('#select_tags').val(id_cate).trigger('change');
                        $('#select_tags').select2();

                        if(data.categories.category_image){
                            $('#avatar').attr('src','{{\Illuminate\Support\Facades\URL::asset('storage/sites/'.request()->id.'/categories')}}/'+data.categories.category_image);
                        }else {
                            $('#avatar').attr('src','{{\Illuminate\Support\Facades\URL::asset('storage/defaultCate.png')}}');
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $(document).on('click','.delete{{preg_replace('/\s+/','',$page_title)}}Category', function (data){
                var id = $(this).data("id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Yes, delete it!"
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "get",
                            url: "{{ asset("admin/categories/delete") }}/" + id,
                            success: function (data) {
                                toastr['success'](data.success, 'Success!');
                                dtTable.draw();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });


            $('#form{{preg_replace('/\s+/','',$page_title)}}Category').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}Category")[0]);
                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}Category').val() == 'create') {
                    $.ajax({
                        data: formData,
                        url: '{{route('categories.create')}}',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                $('#form{{preg_replace('/\s+/','',$page_title)}}Category').trigger("reset");
                                toastr['success'](data.success, 'Success!');
                                $('#modal{{preg_replace('/\s+/','',$page_title)}}Category').modal('hide');
                                dtTable.draw();
                            }
                            if (data.errors) {
                                for (var count = 0; count < data.errors.length; count++) {
                                    toastr['error'](data.errors[count], 'Error!',);
                                }
                            }
                        }
                    });
                }
                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}Category').val() == 'update') {
                    $.ajax({
                        data: formData,
                        url: '{{route('categories.update')}}',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                $('#form{{preg_replace('/\s+/','',$page_title)}}Category').trigger("reset");
                                toastr['success'](data.success, 'Success!');
                                $('#modal{{preg_replace('/\s+/','',$page_title)}}Category').modal('hide');
                                dtTable.draw();
                            }
                            if (data.errors) {
                                for (var count = 0; count < data.errors.length; count++) {
                                    toastr['error'](data.errors[count], 'Error!',);
                                }
                            }
                        }
                    });
                }

            });

            document.getElementById('category_checked_ip').onclick = function(e){
                var category_name = $('#category_name').val();
                if (this.checked){
                    $('#category_name').val(category_name.replaceAll('Phace_', ''))
                }
                else{
                    $('#category_name').val('Phace_'+category_name)
                }
            };

            var dtTable_listips = $('#table{{preg_replace('/\s+/','',$page_title)}}_listips').DataTable({
                processing: true,
                serverSide: true,
                displayLength: 50,
                ajax: {
                    url: "{{route('sites.getIndexListIPs')}}",
                    type: "post",
                    data: {
                        "id": "{{ request()->id }}"
                    }
                },
                columns: [
                    // columns according to JSON
                    { data: 'ip_address',  className: "align-middle", },
                    { data: 'count',className: "align-middle",},
                    { data: 'updated_at',className: "align-middle",},
                ],
                order: [1, 'asc'],
            });
        })
        function changeImg(input){
            if(input.files && input.files[0]){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#avatar').attr('src',e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection
