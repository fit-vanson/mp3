@extends('layouts.master')

@section('title') {{$page_title}}  @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
{{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}

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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-1">
{{--                            <table id="tech-companies-1" class="table table-striped">--}}
                            <table id="table{{preg_replace('/\s+/','',$page_title)}}" class="table table-bordered dt-responsive"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width: 10%">Project</th>
                                    <th style="width: 10%">Image</th>
                                    <th style="width: 30%">Name</th>
                                    <th style="width: 10%">Ads</th>
                                    <th style="width: 15%">Sort</th>
                                    <th style="width: 8%"> Count Categories</th>
                                    <th style="width: 7%"> Count Wallpapers</th>
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



    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal{{preg_replace('/\s+/','',$page_title)}}" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="{{preg_replace('/\s+/','',$page_title)}}ModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="card-body">
                    <form id="form{{preg_replace('/\s+/','',$page_title)}}">
                        <input type="hidden" name="id" id="id">
                        <input  id="image" type="file" name="image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                        <img id="avatar" width="200px" src="{{asset('assets/images/1.png')}}">
                        <div class="form-group">
                            <label>Site name</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" placeholder="zxcv">
                        </div>

                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" class="form-control" id="site_web" name="site_web" placeholder="zxcv.com" required>
                        </div>

                        <div class="form-group">
                            <label>Project ID AIO</label>
                            <input type="text" class="form-control" id="site_project" name="site_project" placeholder="DAxxx-yy" required>
                        </div>

                        <div class="form-group">
                            <label class="d-block mb-3">Ads :</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="site_ads_chplay" name="site_type_ads" class="custom-control-input" checked value="0">
                                <label class="custom-control-label" for="site_ads_chplay">CHPlay</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="site_ads_oppo" name="site_type_ads" class="custom-control-input" value="1">
                                <label class="custom-control-label" for="site_ads_oppo">Oppo</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="site_ads_vivo" name="site_type_ads" class="custom-control-input" value="2">
                                <label class="custom-control-label" for="site_ads_vivo">Vivo</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="site_ads_xiaomi" name="site_type_ads" class="custom-control-input" value="3">
                                <label class="custom-control-label" for="site_ads_xiaomi">Xiaomi</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="site_ads_huawei" name="site_type_ads" class="custom-control-input" value="4">
                                <label class="custom-control-label" for="site_ads_huawei">Huawei</label>
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

    <script>

        $(document).ready(function() {
            $(".select2").select2({
                closeOnSelect: false,
            });
            $('#avatar').click(function(){
                $('#image').click();
            });
        });
        function changeImg(input){
            if(input.files && input.files[0]){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#avatar').attr('src',e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var dtTable = $('#table{{preg_replace('/\s+/','',$page_title)}}').DataTable({
                processing: true,
                serverSide: true,
                displayLength: 50,
                ajax: {
                    url: "{{route('sites.getIndex')}}",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    { data: 'site_project',className: "align-middle"},
                    { data: 'site_image',className: "align-middle text-center " },
                    { data: 'site_name',  className: "align-middle", },
                    { data: 'site_ads',className: "align-middle"},
                    { data: 'site_sort',className: "align-middle",orderable: false},
                    { data: 'categories_count',className: "align-middle",},
                    { data: 'wallpapers_count',className: "align-middle",orderable: false},
                    { data: 'action',className: "align-middle text-center ",orderable: false }
                ],
                order: [1, 'asc'],

                fnDrawCallback: function () {
                    $('.image-popup-no-margins').magnificPopup({
                        type: 'image',
                        closeOnContentClick: true,
                        closeBtnInside: false,
                        fixedContentPos: true,
                        mainClass: 'mfp-no-margins mfp-with-zoom',
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

            $(".dataTables_filter input")
                .unbind() // Unbind previous default bindings
                .bind("input", function(e) { // Bind our desired behavior
                    // If the length is 3 or more characters, or the user pressed ENTER, search
                    if(this.value.length >= 3 || e.keyCode == 13) {
                        // Call the API search function
                        dtTable.search(this.value).draw();
                    }
                    // Ensure we clear the search if they backspace far enough
                    if(this.value == "") {
                        dtTable.search("").draw();
                    }
                    return;
                });



            $('.create{{preg_replace('/\s+/','',$page_title)}}').click(function () {
                $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Add {{$page_title}}");
                $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("create");
                $('#id').val('');
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                $(".select2").select2({
                    closeOnSelect: false,
                });
            });

            $('#form{{preg_replace('/\s+/','',$page_title)}}').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}")[0]);
                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val() == 'create') {
                    $.ajax({
                        data: formData,
                        url: '{{route('sites.create')}}',
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
                }
                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val() == 'update') {
                    $.ajax({
                        data: formData,
                        url: '{{route('sites.update')}}',
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
                }

                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val() == 'copy') {
                    $.ajax({
                        data: formData,
                        url: '{{route('sites.clone')}}',
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
                }

            });


            $(document).on('click','.delete{{preg_replace('/\s+/','',$page_title)}}', function (data){
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

                    $.ajax({
                        type: "get",
                        url: "{{ asset("admin/sites/delete") }}/"+id,
                        success: function (data) {
                            toastr['success'](data.success, 'Success!');
                            dtTable.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });
            });

            $(document).on('click','.edit{{preg_replace('/\s+/','',$page_title)}}', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/edit") }}/"+id,
                    success: function (data) {

                        $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Edit {{$page_title}}");
                        $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("update");

                        $('#id').val(data.site.id);
                        $('#site_name').val(data.site.site_name);
                        $('#site_web').val(data.site.site_web);
                        $('#site_project').val(data.site.site_project);


                        if(data.site.site_type_ads == 0){
                            $('#site_ads_chplay').prop('checked', true);
                        }else if(data.site.site_type_ads == 1) {
                            $('#site_ads_oppo').prop('checked', true);
                        }else if(data.site.site_type_ads == 2) {
                            $('#site_ads_vivo').prop('checked', true);
                        }else if(data.site.site_type_ads == 3) {
                            $('#site_ads_xiaomi').prop('checked', true);
                        }else if(data.site.site_type_ads == 4) {
                            $('#site_ads_huawei').prop('checked', true);
                        }

                        $('#avatar').attr('src','../storage/sites/'+data.site.site_image);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $(document).on('click','.copy{{preg_replace('/\s+/','',$page_title)}}', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/edit") }}/"+id,
                    success: function (data) {
                        $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Copy {{$page_title}}");
                        $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("copy");

                        $('#id').val(data.site.id);
                        $('#site_name').val(data.site.site_name);
                        $('#site_web').val(data.site.site_web);
                        $('#site_project').val(data.site.site_project);
                        $('#avatar').attr('src','../storage/sites/'+data.site.site_image);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $(document).on('click','.changeAds', function (data){
                var id = $(this).data("id");
                var ads = $(this);
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/change_ajax") }}/"+id+"?action=ads",
                    success: function (data) {
                        ads.replaceWith(data.ads)
                    },
                    error: function (data) {
                    }
                });
            });
            $(document).on('click','.change_load_feature', function (data){
                var id = $(this).data("id");
                var btn = $(this);
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/change_ajax") }}/"+id+"?action=load_feature",
                    success: function (data) {
                        btn.replaceWith(data.btn)
                    },
                    error: function (data) {
                    }
                });
            });
            $(document).on('click','.change_load_categories', function (data){
                var id = $(this).data("id");
                var btn = $(this);
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/change_ajax") }}/"+id+"?action=categories",
                    success: function (data) {
                        btn.replaceWith(data.btn)
                    },
                    error: function (data) {
                    }
                });
            });
            $(document).on('click','.change_load_wallpapers', function (data){
                var id = $(this).data("id");
                var btn = $(this);
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/sites/change_ajax") }}/"+id+"?action=wallpapers",
                    success: function (data) {
                        btn.replaceWith(data.btn)
                    },
                    error: function (data) {
                    }
                });
            });


        })
    </script>

@endsection
