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
                        <form method="post" action="{{route('musics.create')}}" enctype="multipart/form-data"
                              class="dropzone" id="form{{preg_replace('/\s+/','',$page_title)}}">
                            @csrf
{{--                            <div class="form-group mb-0">--}}
{{--                                <label class="control-label">Tags Select</label>--}}
{{--                                <select class="select2 form-control select2-multiple" id="select_tags"--}}
{{--                                        name="select_tags[]" multiple="multiple"--}}
{{--                                        data-placeholder="Choose ...">--}}
{{--                                    @foreach($tags as $tag)--}}
{{--                                        <option value="{{$tag->id}}">{{$tag->tag_name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}

{{--                            </div>--}}
                            <div class="fallback">
                                <input name="file" type="file" multiple="multiple">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <form id="form{{preg_replace('/\s+/','',$page_title)}}EditMusic">
                        <input type="hidden" name="id" id="id">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" id="music_name" name="music_name" >
                        </div>

                        <div class="form-group">
                            <label>Link Url Image</label>
                            <input type="text" class="form-control" id="music_url_image" name="music_url_image" >
                        </div>

                        <div class="form-group">
                            <label>Link 1</label>
                            <input type="text" class="form-control" id="music_link_1" name="music_link_1" >
                        </div>

                        <div class="form-group">
                            <label>Link 2</label>
                            <input type="text" class="form-control" id="music_link_2" name="music_link_2" >
                        </div>

                        <div class="form-group">
                            <label>ID YouTuBe</label>
                            <input type="text" class="form-control" id="music_id_ytb" name="music_id_ytb" >
                        </div>

                        <div class="form-group">
                            <label class="control-label">Tags Select</label>
                            <select class="select2 form-control select2-multiple" id="select_tags_edit"
                                    name="select_tags[]" multiple="multiple"
                                    data-placeholder="Choose ..." style="width: 100%">
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
                                @endforeach
                            </select>
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

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal{{preg_replace('/\s+/','',$page_title)}}update_multiple" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0"> Update Multiple </h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="card-body">
                    <form id="form{{preg_replace('/\s+/','',$page_title)}}update_multiple">
                        <div class="form-group">
                            <p class="text-muted"> <code>{id} | {link1} | {link2} | {id Ytb}</code>
                            </p>
                            <textarea id="update_multiple" name="update_multiple" class="form-control" rows="20" ></textarea>
                        </div>


                        <div class="form-group mb-0">
                            <div>
                                <button type="submit" id="saveBtn{{preg_replace('/\s+/','',$page_title)}}update_multiple" class="btn btn-success waves-effect waves-light mr-1">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

{{--    <div class="row align-items-center" style="padding-bottom: 10px">--}}
{{--        <div class="col-sm-12">--}}
{{--            <div class="float-right" >--}}
{{--                <a href="{{route('wallpapers.index')}}" class="btn btn-outline-primary waves-effect waves-light" type="button">--}}
{{--                    <i class="ti-list"></i>--}}
{{--                </a>--}}
{{--                <a href="{{route('wallpapers.index')}}?view=grid" class="btn btn-outline-primary waves-effect waves-light" type="button">--}}
{{--                    <i class="ti-layout-grid2"></i>--}}
{{--                </a>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}




    <div class="load_ajax">
        @if(isset($data))

            <div class="infinite-scroll">
                <div class="row">
                    @foreach($data as $item)
                        <div class="col-md-6 col-lg-6 col-xl-2">
                            <!-- Simple card -->
                            <div class="card" id="card_image_{{$item->id}}">
                                <a class="image-popup-no-margins" href="{{url('/storage/wallpapers').'/'.$item->wallpaper_image}}">
                                    <img class="img-fluid" alt="{{$item->wallpaper_name}}" src="{{url('/storage/wallpapers/thumbnails').'/'.$item->wallpaper_image}}">
                                </a>

                                <div class="card-body">

                                    <span class="card-title" style="font-size: larger; font-weight: bold">{{$item->wallpaper_name}}</span>
                                    @if($item->wallpaper_status == 1)
                                        <i class="fas fa-check-circle" style="color: green"></i>
                                    @elseif($item->wallpaper_status == 0)
                                        <i class="fas fa-times-circle" style="color: red"></i>
                                    @endif

                                    <a href="javascript:void(0)" onclick="deleteWallpaper('{{$item->id}}')" class="btn btn-danger float-right"><i class="ti-trash"></i></a>

                                    <p>
                                        <?php
                                        $tags = [];
                                        foreach ($item->tags as $tag){
                                        ?>
                                        <span class="badge badge-pill badge-success">{{$tag->tag_name}}</span>
                                        <?php
                                        }
                                        ?>
                                    </p>





                                </div>
                            </div>

                        </div><!-- end col -->
                    @endforeach
                    <?php
                        $search = null;
                        if (isset($_GET['search'])){
                            $search = $_GET['search'];
                        }
                    ?>
                    {{$data->appends(['view' => 'grid','search'=>$search])->links()}}
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
                                                <th style="width: 5%">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" name="select_all" value="1" id="select_all" />
                                                        <label class="custom-control-label" for="select_all"></label>
                                                    </div>
                                                </th>
                                                <th style="width: 15%">File</th>
                                                <th style="width: 15%">Name</th>
                                                <th style="width: 10%">Link</th>
                                                <th style="width: 10%">View Count</th>
                                                <th style="width: 10%">Like Count</th>
                                                <th style="width: 10%">
                                                    <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="null_tag" value="0"  id="null_tag" />
                                                    <label class="custom-control-label" for="null_tag">Tags</label>
                                                    </div>
                                                </th>
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
    </div>

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

    <script>
        Dropzone.autoDiscover = false;
        $(".select2").select2({});
        // $(".select2").select2({
        //     // closeOnSelect: false,
        //     tags: true,
        //     tokenSeparators: [',', ';'],
        //     createTag: function (params) {
        //         var term = $.trim(params.term);
        //
        //         if (term === '') {
        //             return null;
        //         }
        //         return {
        //             id: term,
        //             text: term,
        //             newTag: true // add additional parameters
        //         }
        //     }
        // }).on("change", function(e) {
        //     var isNew = $(this).find('[data-select2-tag="true"]');
        //     if(isNew.length && $.inArray(isNew.val(), $(this).val()) !== -1){
        //         $.ajax({
        //             url: '/admin/tags/create',
        //             type: 'post',
        //             dataType: 'json',
        //             data: {tag_name: isNew.val()},
        //             success: function (data) {
        //                 if (data.success) {
        //                     isNew.replaceWith('<option selected value="'+data.tag.id+'">'+data.tag.tag_name+'</option>');
        //                 }
        //                 if (data.errors) {
        //                     console.log(data.errors)
        //                 }
        //             }
        //         });
        //     }
        // });

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
                    url: "{{route('musics.getIndex')}}",
                    type: "post",
                    data: function(data){
                        var active = $('#null_tag').prop("checked") ? 1 : 0 ;
                        data.null_tag = $('#null_tag').val();
                    },
                },
                columns: [
                    // columns according to JSON
                    {data: 'id',className: "text-center align-middle "},
                    {data: 'music_file',className: "text-center "},
                    {data: 'music_name', className: "align-middle",},
                    {data: 'music_link', className: "align-middle",},
                    {data: 'music_view_count', className: "align-middle"},
                    {data: 'music_like_count', className: "align-middle"},
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
                    // '<"col-sm-12 col-md-3"<"button-items"B>>' +
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
                // buttons: [
                //     {
                //         text: 'Delete',
                //         className: 'deleteSelect btn btn-danger',
                //         attr: {
                //             'type': 'submit'
                //         },
                //         init: function (api, node, config) {
                //             $(node).removeClass('btn-secondary');
                //         }
                //     }
                // ],
                order: [2, 'desc'],
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
                dtTable.search(search).draw();
            }

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


            // Handle click on "Select all" control
            $('#select_all').on('click', function(){
                // Check/uncheck all checkboxes in the table
                var rows = dtTable.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });



            $('#null_tag').on('change', function(){
                var active = $('#null_tag').prop("checked") ? 1 : 0 ;
                $('#null_tag').val(active);
                $('#table{{preg_replace('/\s+/','',$page_title)}}').DataTable().ajax.reload();
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
                    if (result.value) {
                        $.ajax({
                            type: "get",
                            url: "{{ asset("admin/musics/delete") }}/" + id,
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


            $(document).on('click', '.update_multiple{{preg_replace('/\s+/','',$page_title)}}', function () {
                $('#modal{{preg_replace('/\s+/','',$page_title)}}update_multiple').modal('show');
            });

            $('#form{{preg_replace('/\s+/','',$page_title)}}update_multiple').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}update_multiple")[0]);
                    $.ajax({
                        data: formData,
                        url: '{{route('musics.update_multiple')}}',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                $('#form{{preg_replace('/\s+/','',$page_title)}}update_multiple').trigger("reset");
                                toastr['success'](data.success, 'Success!');
                                $('#modal{{preg_replace('/\s+/','',$page_title)}}update_multiple').modal('hide');
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

            $(document).on('click', '.edit{{preg_replace('/\s+/','',$page_title)}}', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/musics/edit") }}/" + id,
                    success: function (data) {
                        $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Edit {{$page_title}}");
                        $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("update");

                        $('#id').val(data.music.id);
                        $('#music_name').val(data.music.music_name);
                        $('#music_link_1').val(data.music.music_link_1);
                        $('#music_url_image').val(data.music.music_url_image);
                        $('#music_link_2').val(data.music.music_link_2);
                        $('#music_id_ytb').val(data.music.music_id_ytb);


                        var id_tags =[];
                        $.each(data.music.tags, function(i, item) {
                            id_tags.push(item.id.toString())
                        });
                        $('#select_tags_edit').val(id_tags).trigger('change');
                        $('#select_tags_edit').select2();


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
                    if (result.value) {
                        $.ajax({
                            data: $('#checkForm').serialize(),
                            url: "{{ route('musics.deleteSelect') }}",
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
                    }
                });


            });


            $('#form{{preg_replace('/\s+/','',$page_title)}}EditMusic').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}EditMusic")[0]);

                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val() == 'update') {
                    $.ajax({
                        data: formData,
                        url: '{{route('musics.update')}}',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                $('#form{{preg_replace('/\s+/','',$page_title)}}EditMusic').trigger("reset");
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

            $('#form{{preg_replace('/\s+/','',$page_title)}}').dropzone(
                {
                    maxFilesize: 20,
                    parallelUploads: 30 ,
                    uploadMultiple: true,
                    acceptedFiles: ".mp3,.txt,.jpg",
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

        function deleteWallpaper(id){
            var $target = $('#card_image_'+id);

            $.ajax({
                type: "get",
                url: "{{ asset("admin/wallpapers/delete") }}/" + id,
                success: function (data) {
                    $target.hide('slow', function(){ $target.remove(); });
                    toastr['success'](data.success, 'Success!');
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>



@endsection
