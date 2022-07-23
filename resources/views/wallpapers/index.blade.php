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



{{--    <style>--}}
{{--        .select2-selection__choice {--}}
{{--            /*margin-top: 0px!important;*/--}}
{{--            /*padding-right: 5px!important;*/--}}
{{--            /*padding-left: 5px!important;*/--}}
{{--            background-color: transparent !important;--}}
{{--            border: none !important;--}}
{{--            border-radius: 4px !important;--}}
{{--            background-color: rgba(0, 255, 13, 0.29) !important;--}}
{{--        }--}}

{{--        .select2-selection__choice__remove:hover {--}}
{{--            background-color: transparent !important;--}}
{{--            color: #ef5454 !important;--}}
{{--        }--}}
{{--    </style>--}}
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
                                <label class="control-label">Tags Select</label>
                                <select class="select2 form-control select2-multiple" id="select_tags"
                                        name="select_tags[]" multiple="multiple"
                                        data-placeholder="Choose ...">
                                    @foreach($tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
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
        </div>
    </div>

    @if(isset($data))

        <div class="infinite-scroll">
            <div class="row">
                @foreach($data as $item)
                    <div class="col-md-6 col-lg-6 col-xl-2">
                        <!-- Simple card -->
                        <div class="card">
                            <a class="image-popup-no-margins" href="{{url('/storage/wallpapers').'/'.$item->wallpaper_image}}">
                                <img class="img-fluid" alt="{{$item->wallpaper_name}}" src="{{url('/storage/wallpapers/thumbnails').'/'.$item->wallpaper_image}}">
                            </a>

                            <div class="card-body">
                                <p>
                                    <span class="card-title" style="font-size: larger; font-weight: bold">{{$item->wallpaper_name}}</span>
                                    @if($item->wallpaper_status == 1)
                                    <i class="fas fa-check-circle" style="color: green"></i>
                                    @elseif($item->wallpaper_status == 0)
                                    <i class="fas fa-times-circle" style="color: red"></i>
                                    @endif
                                </p>

                                <?php
                                $tags = [];
                                foreach ($item->tags as $tag){
                                ?>
                                    <span class="badge badge-pill badge-success">{{$tag->tag_name}}</span>
                                <?php
                                }
                                ?>

                            </div>
                        </div>

                    </div><!-- end col -->
                @endforeach
                    {{ $data->appends(['view' => 'grid'])->links() }}
            </div>
        </div>


    @else

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-1">
                            <form id="checkForm" name="checkForm">
                                <table id="table{{preg_replace('/\s+/','',$page_title)}}"
                                       class="table table-bordered dt-responsive"
                                       style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="select_all" value="1" id="select_all" />
                                                <label class="custom-control-label" for="select_all"></label>
                                            </div>
                                        </th>
                                        <th style="width: 20%">Image</th>
                                        <th style="width: 20%">Name</th>
                                        <th style="width: 10%">View Count</th>
                                        <th style="width: 10%">Like Count</th>
                                        <th style="width: 10%">Extension</th>
                                        <th style="width: 15%">Tags</th>
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

    @endif



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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>




{{--    <script type="text/javascript">--}}
{{--        $('ul.pagination').hide();--}}
{{--        $(function() {--}}
{{--            $('.infinite-scroll').jscroll({--}}
{{--                autoTrigger: true,--}}
{{--                // loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',--}}
{{--                padding: 0,--}}
{{--                nextSelector: '.pagination li.active + li a',--}}
{{--                contentSelector: 'div.infinite-scroll',--}}
{{--                callback: function() {--}}
{{--                    $('ul.pagination').remove();--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}






    <script>
        Dropzone.autoDiscover = false;

        $(".select2").select2({
            // closeOnSelect: false,
            tags: true,
            tokenSeparators: [',', ' '],
            createTag: function (params) {
                var term = $.trim(params.term);

                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true // add additional parameters
                }
            }
        }).on("change", function(e) {
            var isNew = $(this).find('[data-select2-tag="true"]');
            if(isNew.length && $.inArray(isNew.val(), $(this).val()) !== -1){
                $.ajax({
                    url: '/admin/tags/create',
                    type: 'post',
                    dataType: 'json',
                    data: {tag_name: isNew.val()},
                    success: function (data) {
                        if (data.success) {
                            isNew.replaceWith('<option selected value="'+data.tag.id+'">'+data.tag.tag_name+'</option>');
                        }
                        if (data.errors) {
                            console.log(data.errors)
                        }
                    }
                });
            }
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
                    {data: 'tags',className: "align-middle",orderable: false,},
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

                columnDefs: [
                    {
                        // For Checkboxes
                        targets: 0,
                        // visible: false,
                        orderable: false,
                        responsivePriority: 3,
                        render: function (data, type, full, meta) {
                            return (
                                '<div class="custom-control custom-checkbox"> ' +
                                    '<input class="custom-control-input" type="checkbox" value="' + [full.id] + '" name="id[]" id="checkbox' +data +'" />' +
                                '<label class="custom-control-label" for="checkbox' +data +'"></label>' +
                                '</div>'
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
                            var tags = data,
                                $output = '';
                            var stateNum = Math.floor(Math.random() * 6) + 1;
                            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                            var $state = states[stateNum];
                            $.each(tags, function(i, item) {
                                $output += ' <span class="badge badge-'+$state+'" style="font-size: 100%">' + item.tag_name + '</span> ';
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

            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const search = urlParams.get('search')
            if(search !== null){
                dtTable.search(search)
                    .draw();
            }


            // Handle click on "Select all" control
            $('#select_all').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = dtTable.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle click on checkbox to set state of "Select all" control
            $('#table{{preg_replace('/\s+/','',$page_title)}} tbody').on('change', 'input[type="checkbox"]', function(){
                // If checkbox is not checked
                if(!this.checked){
                    var el = $('#select_all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    if(el && el.checked && ('indeterminate' in el)){
                        // Set visual state of "Select all" control
                        // as 'indeterminate'
                        el.indeterminate = true;
                    }
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
                        url: "{{ asset("admin/wallpapers/delete") }}/" + id,
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
                    url: "{{ asset("admin/wallpapers/edit") }}/" + id,
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

            $('ul.pagination').hide();
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
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
        })
    </script>

@endsection
