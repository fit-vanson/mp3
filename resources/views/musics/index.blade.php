@extends('layouts.master')
<?php
$page_title = $header['title'];
$button = $header['button'];
?>
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


{{--    <div class="row">--}}
{{--        <div class="col-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="mb-5">--}}
{{--                        <form method="post" action="{{route('musics.create')}}" enctype="multipart/form-data"--}}
{{--                              class="dropzone" id="form{{preg_replace('/\s+/','',$page_title)}}">--}}
{{--                            @csrf--}}
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
{{--                            <div class="fallback">--}}
{{--                                <input name="file" type="file" multiple="multiple">--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modalMusicEdit" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="ModalEditLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="card-body">
                    <form id="formEditMusic">
                        <input type="hidden" name="id" id="id">

{{--                        <div class="form-group">--}}
{{--                            <label>ID YouTuBe</label>--}}
{{--                            <input type="text" class="form-control" id="music_id_ytb" name="music_id_ytb" >--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label>Link Url Image</label>
                            <input  type="text" class="form-control" id="music_thumbnail_link" name="music_thumbnail_link" >
                            <img id="link_thumbnail"  src=""/>
                        </div>


                        <div class="form-group">
                            <label class="control-label">Tags Select</label>
                            <select  class="select2 form-control select2-multiple" id="select_tags_edit"
                                    name="select_tags[]" multiple="multiple"
                                    data-placeholder="Choose ..." style="width: 100%" required   >
                                @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="form-group mb-0">
                            <div>
                                <button type="submit" id="saveBtnEdit" class="btn btn-primary waves-effect waves-light mr-1">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-1">
                            <table id="tableMusics" class="table table-bordered dt-responsive"
                                   style="width: 100%;">
                                <thead>
                                <tr>
{{--                                    <th >Name</th>--}}
                                    <th >Image</th>
                                    <th >ID YTB</th>
                                    <th >View</th>
                                    <th >Download</th>
                                    <th >Like</th>
                                    <th style="width: 15%">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="null_tag" value="0"  id="null_tag" />
                                            <label class="custom-control-label" for="null_tag">Tags</label>
                                        </div>
                                    </th>
                                    <th >Action</th>
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

    <!-- Create from YTB-->

    <div class="modal fade" id="modalCreateYTB" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="CreateYTBLabel">Thêm mới từ YTB</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="card-body">
                    <form id="formCreateYTB" name="formCreateYTB" class="form-horizontal" enctype="multipart/form-data">
                        <div class="mb-4">
                            <div class="inner form-group">
                                <label>LIST ID YouTuBe  <code>( | , )</code></label>
                                <div class="inner mb-3 row">
                                    <div class="col-md-10 col-8">
                                        <input type="text"  id="music_id_ytb" name="music_id_ytb" class="inner form-control" placeholder="Enter ID YTB ...">
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <a href="javascript:void(0)" class="btn btn-primary btn-block inner getInfoID">Get Info</a>
                                    </div>

                                </div>
                            </div>

                            <div class="inner form-group">
                                <label >Tags Select</label>
                                <select class="form-control select2-multiple" id="select_tags" style="width: 100%"
                                        name="select_tags[]" multiple="multiple"
                                        data-placeholder="Choose ..." required>
                                    @foreach($tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="tablist_result_getInfo" style="display: none">
                            <ul class="nav nav-tabs" role="tablist" id="nav_tabs_result_getInfo"></ul>
                            <div class="tab-content" id="tab_content_result_getInfo"></div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save changes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
{{--                        <div class="form-group mb-0">--}}
{{--                            <button type="submit" id="saveBtnCreateYTB" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>--}}
{{--                        </div>--}}
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
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

    <script>
        // Dropzone.autoDiscover = false;
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            var dtTable = $('#tableMusics').DataTable({
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
                    // {data: 'id',className: "text-center align-middle "},
                    {data: 'music_thumbnail_link',className: "text-center align-middle "},
                    {data: 'music_id_ytb',className: "text-center "},
                    {data: 'music_view_count', className: "align-middle",},
                    {data: 'music_download_count', className: "align-middle",},
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
                        targets: 5,
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
                order: [2, 'desc'],
                fnDrawCallback: function () {
                    $('.popup-music').magnificPopup({
                        disableOn: 100,
                        type: 'iframe',
                        mainClass: 'mfp-fade',
                        removalDelay: 160,
                        preloader: false,

                        fixedContentPos: false
                    });
                },

                fnRowCallback: function (nRow, aData) {
                    switch (aData.status){
                        case 1:
                            $('td', nRow).css('background-color', 'rgba(253,0,0,0.07)');
                            break;
                        default:
                            $('td', nRow).css('background-color', 'rgba(255,255,255,0)');
                            break;
                    }
                },


            });

            $('#null_tag').on('change', function(){
                var active = $('#null_tag').prop("checked") ? 1 : 0 ;
                $('#null_tag').val(active);
                $('#table{{preg_replace('/\s+/','',$page_title)}}').DataTable().ajax.reload();
            });



            $(document).on('click', '#createYTB', function () {
                $('#modalCreateYTB').modal('show');
                $('#select_tags').select2();
                // $('#saveBtnCreateYTB').hide();
                // $('#formCreateYTB').trigger("reset");
                $('#tab_content_result_getInfo').html('');
                $('#nav_tabs_result_getInfo').html('');
            });
            $(document).on('click', '.getInfoID', function () {
                const _id = $("#music_id_ytb").val();

                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/musics/get-info-ytb?ytb_id=") }}" + btoa(_id),
                    success: function (data) {
                        $('#tablist_result_getInfo').show();
                        $('#saveBtnCreateYTB').show();
                        let nav_tabs_result_getInfo = '';
                        let tab_content_result_getInfo = '';
                        let active = '';
                        $.each(data, function (k,v){

                            active = k==0 ? 'active':'';

                            nav_tabs_result_getInfo +=
                                '<li class="nav-item" role="presentation">'+
                                '<a class="nav-link '+active+'"  data-toggle="tab"  href="#tab_'+v.videoId+'" role="tab" id="nav_'+v.videoId+'">'+
                                '<span class="d-none d-sm-block">'+v.videoId+'</span>'+
                                '</a>'+
                                '</li>';

                            tab_content_result_getInfo +=
                                '<div class="tab-pane p-3 '+active+'" id="tab_' + v.videoId + '" role="tabpanel">'+
                                    '<div  class="row">' +




                                        '<div class="form-group col-lg-4">' +
                                        '<h6 class="mt-0 header-title">Download</h6>'+
                                        // '<label for="name">Download </label>' +
                                        '<input type="checkbox" id="getInfo_'+v.videoId+'_download" name="getInfo['+v.videoId+'][download]" switch="none" >'+
                                        '<label for="getInfo_'+v.videoId+'_download" data-on-label="Yes" data-off-label="No"></label>'+
                                        '</div>'+


                                        '<input id="getInfo_'+v.videoId+'_url_audio" name="getInfo['+v.videoId+'][url_audio]" hidden value="'+v.url_audio+'">' +

                                        '<div class="form-group col-lg-4">' +
                                        '<label for="name">Time </label>' +
                                        '<input type="text" id="getInfo_'+v.videoId+'_lengthSeconds" name="getInfo['+v.videoId+'][lengthSeconds]" disabled class="form-control" value="'+v.lengthSeconds+'" >' +
                                        '</div>'+
                                        '<div class="form-group col-lg-4">' +
                                        '<label for="name">View Count</label>' +
                                        '<input type="text" id="getInfo_'+v.videoId+'_viewCount" name="getInfo['+v.videoId+'][viewCount]" class="form-control"   value="'+v.viewCount+'">' +
                                        '</div>'+

                                        '<div class="form-group col-lg-6">' +
                                        '<img id="getInfo_'+v.videoId+'_image" name="getInfo['+v.videoId+'][image]" width="300px" src="'+v.image+'">' +
                                        '</div>'+

                                        '<div class="form-group col-lg-6">' +
                                        '<label for="name">Title</label>' +
                                        '<textarea type="text" id="getInfo_'+v.videoId+'_title" rows="6" name="getInfo['+v.videoId+'][title]" class="form-control" >' +v.title +'</textarea>'+
                                        '</div>'+

                                        '<div class="form-group col-lg-6">' +
                                        '<label for="name">Keywords</label>' +
                                        '<textarea  id="getInfo_'+v.videoId+'_keywords" name="getInfo['+v.videoId+'][keywords]" class="form-control"  rows="8">' +v.keywords +'</textarea>'+
                                        '</div>'+

                                        '<div class="form-group col-lg-6">' +
                                        '<label for="name">Description</label>' +
                                        '<textarea  id="getInfo_'+v.videoId+'_description" name="getInfo['+v.videoId+'][description]" class="form-control"  rows="8">' +v.shortDescription +'</textarea>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                            // $('#tab_content_result_getInfo').append(html);
                        })
                        $('#nav_tabs_result_getInfo').html(nav_tabs_result_getInfo);
                        $('#tab_content_result_getInfo').html(tab_content_result_getInfo);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });


                console.log(_id);
            });

            $('#formCreateYTB').on('submit', function (event) {
                event.preventDefault();
                const formData = $('#formCreateYTB').serialize();
                // var formData = new FormData($("formCreateYTB")[0]);
                $.ajax({
                    data: formData,
                    url: '{{route('musics.createYTB')}}',
                    type: "POST",
                    dataType: 'json',
                    // processData: false,
                    // contentType: false,
                    success: function (data) {
                        console.log(data)
                        if (data.success) {
                            $('#formCreateYTB').trigger("reset");
                            toastr['success'](data.success, 'Success!');
                            $('#modalCreateYTB').modal('hide');
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



            $(document).on('click', '.editMusic', function (data) {
                $('#formEditMusic').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/musics/edit") }}/" + id,
                    success: function (data) {
                        console.log(data)
                        $('#modalMusicEdit').modal('show');
                        $('#ModalEditLabel').html("Edit "+data.music_id_ytb);
                        $('#saveBtnEdit').val("update");

                        $('#id').val(data.id);
                        // $('#music_id_ytb').val(data.music_id_ytb);
                        $('#music_thumbnail_link').val(data.music_thumbnail_link);
                        $('#link_thumbnail').attr("src", data.music_thumbnail_link);



                        var id_tags =[];
                        $.each(data.tags, function(i, item) {
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

            $('#formEditMusic').on('submit', function (event) {
                event.preventDefault();
                const formData = $('#formEditMusic').serialize();

                $.ajax({
                    data: formData,
                    url: '{{route('musics.update')}}',
                    type: "POST",
                    dataType: 'json',
                    // processData: false,
                    // contentType: false,
                    success: function (data) {
                        console.log(data)
                        if (data.success) {
                            $('#formEditMusic').trigger("reset");
                            toastr['success'](data.success, 'Success!');
                            $('#modalMusicEdit').modal('hide');
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

            $(document).on('click', '.deleteMusic', function (data) {
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



        })
    </script>



@endsection
