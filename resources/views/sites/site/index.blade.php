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
                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">
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
                            <a class="nav-link" data-toggle="tab" href="#ads-1" role="tab">
                                <span class="d-block d-sm-block"><i class="fas fa-ad"></i></span>
                                <span class="d-none d-sm-block">Ads</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#categories-1" role="tab">
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
                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
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
                                <label class="d-block mb-3">Load Feature</label>
                                <form id="form{{preg_replace('/\s+/','',$page_title)}}_load_view_by">
                                    <input type="hidden" name="id" id="id_load_view_by" value="{{$site->id}}">
                                    <?php
                                        $loads = ['Random','Manual','Most View','Feature Wallpaper'];
                                        foreach ($loads as $key=>$load){
                                    ?>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio"  id="load_feature_{{$key}}" name="load_view_by" class="custom-control-input" value="1" {{$site->load_view_by == $key ? 'checked' : ''}} >
                                            <label class="custom-control-label" for="load_feature_{{$key}}">{{$load}}</label>
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
                                        <a class="float-left" href="{{asset('storage/featureimages/'.\Illuminate\Support\Str::slug($site->site_web).'/'.$feature_image.'')}}" title="{{$site->site_web}}">
                                            <img src="{{asset('storage/featureimages/'.\Illuminate\Support\Str::slug($site->site_web).'/'.$feature_image.'')}}" alt="" width="150">
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane p-3" id="ads-1" role="tabpanel">
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
                        <div class="tab-pane p-3" id="categories-1" role="tabpanel">
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
                                                            <th style="width: 40%">Image</th>
                                                            <th style="width: 30%">Name</th>
                                                            <th style="width: 10%">Real</th>
                                                            <th style="width: 10%">Image Count</th>
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
                        <input type="hidden" name="category_id" id="id_Category">
                        <input type="hidden" name="site_id" id="id_Site">
                        <input  id="image" type="file" name="image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                        <img id="avatar" width="200px" src="{{asset('assets/images/1.png')}}">
{{--                        <div class="form-group">--}}
{{--                            <label>Category name</label>--}}
{{--                            <input type="text" class="form-control" id="category_name" name="category_name" disabled>--}}
{{--                        </div>--}}

                        <div class="form-group mb-0">
                            <div>
                                <button type="submit" id="saveBtn{{preg_replace('/\s+/','',$page_title)}}Category" class="btn btn-primary waves-effect waves-light mr-1">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect">
                                    Cancel
                                </button>
                            </div>
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
                    url: "{{ asset("sites/change-ads") }}/" + id,

                    success: function (data) {
                        $(".site_ads").load(" .site_ads");
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
                var id = $('#id').val();
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

            {{--document.body.addEventListener('change', function (e) {--}}
            {{--    var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}_load_view_by")[0]);--}}
            {{--    $.ajax({--}}
            {{--        data: formData,--}}
            {{--        url: '{{route('sites.update_site')}}',--}}
            {{--        type: "POST",--}}
            {{--        dataType: 'json',--}}
            {{--        processData: false,--}}
            {{--        contentType: false,--}}
            {{--        success: function (data) {--}}
            {{--            if (data.success) {--}}
            {{--                toastr['success'](data.success, 'Success!');--}}
            {{--            }--}}
            {{--            if (data.errors) {--}}
            {{--                for (var count = 0; count < data.errors.length; count++) {--}}
            {{--                    toastr['error'](data.errors[count], 'Error!',);--}}
            {{--                }--}}
            {{--            }--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
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
                    { data: 'wallpaper_count',className: "align-middle",},
                    { data: 'action',className: "align-middle text-center ", }
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


            $(document).on('click','.editSiteCategory', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}Category').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("categories/edit") }}/"+id,
                    success: function (data) {
                        $('#modal{{preg_replace('/\s+/','',$page_title)}}Category').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}CategoryModalLabel').html("Edit Category {{$page_title}}: "+ data.categories.category_name);
                        $('#id_Category').val(data.categories.id);
                        $('#id_Site').val({{ request()->id }});
                        // $('#category_name').val(data.categories.category_name);
                        $('#avatar').attr('src','{{\Illuminate\Support\Facades\URL::asset('storage/categories')}}/'+data.categories.category_image);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('#form{{preg_replace('/\s+/','',$page_title)}}Category').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}Category")[0]);

                    $.ajax({
                        data: formData,
                        url: '{{route('sites.update_category')}}',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                                toastr['success'](data.success, 'Success!');
                                $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('hide');
                                dtTable.draw();
                            }
                            if (data.errors) {
                                for (var count = 0; count < data.errors.length; count++) {
                                    toastr['error'](data.errors[count], 'Error!',);
                                }
                            }
                        }
                    });


            });

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
