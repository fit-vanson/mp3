@extends('layouts.master')

@section('title') {{$page_title}}  @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Dropzone css -->
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>

    <style>
        .select2-selection__choice {
            /*margin-top: 0px!important;*/
            /*padding-right: 5px!important;*/
            /*padding-left: 5px!important;*/
            background-color: transparent !important;
            border: none !important;
            border-radius: 4px !important;
            background-color: rgba(0, 255, 13, 0.29) !important;
        }

        .select2-selection__choice__remove:hover {
            background-color: transparent !important;
            color: #ef5454 !important;
        }
    </style>
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

                    <div class="mb-5">
                        <form method="post" action="{{route('wallpapers.create')}}" enctype="multipart/form-data"
                              class="dropzone" id="form{{preg_replace('/\s+/','',$page_title)}}">
                            @csrf
                            <div class="form-group mb-0">
                                <label class="control-label">Categories Select</label>
                                <select class="select2 form-control select2-multiple" id="select_categories"
                                        name="select_categories[]" required multiple="multiple"
                                        data-placeholder="Choose ...">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="fallback">
                                <input name="file" type="file" multiple="multiple">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-1">
                            <form id="checkForm" name="checkForm">
                            {{--                            <table id="tech-companies-1" class="table table-striped">--}}
                                <table id="table{{preg_replace('/\s+/','',$page_title)}}"
                                       class="table table-bordered dt-responsive"
                                       style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th style="width: 20%">Image</th>
                                        <th style="width: 20%">Name</th>
                                        <th style="width: 10%">View Count</th>
                                        <th style="width: 10%">Like Count</th>
                                        <th style="width: 10%">Extension</th>
                                        <th style="width: 15%">Categories</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->



@endsection

@section('script')
    <!-- Plugins js -->

    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

    <script>
        Dropzone.autoDiscover = false;

        $(".select2").select2({
            closeOnSelect: false,
        });
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
                    url: "{{route('wallpapers.getIndex')}}",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    {data: 'id',className: "text-center align-middle "},
                    {data: 'wallpaper_image',className: "text-center "},
                    {data: 'wallpaper_name', className: "align-middle",},
                    {data: 'wallpaper_view_count', className: "align-middle",},
                    {data: 'wallpaper_like_count', className: "align-middle",},
                    {data: 'image_extension', className: "align-middle",},
                    {data: 'categories',className: "align-middle",orderable: false,},
                    {data: 'action', className: "align-middle text-center ",orderable: false,}
                ],
                dom:
                    '<"d-flex justify-content-between mx-2 row mt-75"' +
                    '<" col-sm-12 col-lg-2 d-flex justify-content-center justify-content-lg-start" l>' +
                    // '<"button-items"B>'+
                    '<"col-sm-12 col-lg-4 ps-xl-75 ps-0"<" d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-3"<"button-items"B>>' +
                    '<"col-sm-12 col-md-3"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',

                // select: {
                //     style: 'multi'
                // },
                columnDefs: [

                    // '<"button-items"B>'+

                    {
                        // For Checkboxes
                        targets: 0,
                        // visible: false,
                        orderable: false,
                        responsivePriority: 3,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="' + [full.id] + '" name="id[]" id="checkbox' +
                                data +
                                '" /><label class="form-check-label" for="checkbox' +
                                data +
                                '"></label></div>'
                            );
                        },
                        checkboxes: {
                            selectAllRender:
                                '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>'
                        }
                    },
                    {
                        targets: 6,
                        responsivePriority: 1,
                        render: function (data) {
                            var categories = data,
                                $output = '';
                            $.each(categories, function(i, item) {
                                $output += ' <p class="badge badge-success">' + item.category_name + '</p><br> ';
                                return i<2;
                            });
                            return $output
                        }
                    },
                ],
                buttons: [
                    {
                        text: 'Delete',
                        className: 'deleteSelect btn btn-danger',
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


            $(document).on('click', '.delete{{preg_replace('/\s+/','',$page_title)}}', function (data) {
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
                        url: "{{ asset("wallpapers/delete") }}/" + id,
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

            $(document).on('click', '.edit{{preg_replace('/\s+/','',$page_title)}}', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("categories/edit") }}/" + id,
                    success: function (data) {
                        $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Edit {{$page_title}}");
                        $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("update");

                        $('#id').val(data.categories.id);
                        $('#category_name').val(data.categories.category_name);
                        $('#category_order').val(data.categories.category_order);
                        $('#category_view_count').val(data.categories.category_view_count);
                        if (data.categories.category_checked_ip == 0) {
                            $('#category_checked_ip').prop('checked', true);
                        } else {
                            $('#category_checked_ip').prop('checked', false);
                        }

                        $('#avatar').attr('src', '../storage/categories/' + data.categories.category_image);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $(document).on('click', '.deleteSelect', function (data) {
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
                        data: $('#checkForm').serialize(),
                        url: "{{ route('wallpapers.deleteSelect') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            dtTable.draw();
                            toastr['success']('', data.success, {
                                showMethod: 'fadeIn',
                                hideMethod: 'fadeOut',
                                timeOut: 2000,
                            });
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });


            });

            $('.createWallpapers').hide()


            $('#form{{preg_replace('/\s+/','',$page_title)}}').dropzone(
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
                            dtTable.clear().draw();
                        });
                    },
                });


        })
    </script>

@endsection
