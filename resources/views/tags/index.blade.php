@extends('layouts.master')
<?php
$page_title = $header['title'];
$button = $header['button'];
?>

@section('title') {{$page_title}}  @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

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
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-1">
                            <table id="table{{preg_replace('/\s+/','',$page_title)}}" class="table table-bordered dt-responsive"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width: 30%">Name</th>
                                    <th style="width: 10%">Music Count</th>
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
                <div class="modal-body">
                    <form id="form{{preg_replace('/\s+/','',$page_title)}}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Tag name</label>
                            <input type="text" class="form-control" id="tag_name" name="tag_name" required>
                        </div>
                        <div class="form-group mb-0">
                            <div>
                                <button type="submit" id="saveBtn{{preg_replace('/\s+/','',$page_title)}}" class="btn btn-primary waves-effect waves-light mr-1">
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

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal{{preg_replace('/\s+/','',$page_title)}}ChangeTags" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="{{preg_replace('/\s+/','',$page_title)}}ChangeTagsModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="form{{preg_replace('/\s+/','',$page_title)}}ChangeTags">
                        <input type="hidden" name="id" id="id_change_tags">
                        <div class="form-group">
                            <label class="control-label">Wallpaper Tags Select</label>
                            <select class="select2 form-control select2-multiple" id="wallpaper_tags_change"
                                    name="wallpaper_tags_change[]" multiple="multiple"
                                    data-placeholder="Choose ..." style="width: 100%">
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Ringtone Tags Select</label>
                            <select class="select2 form-control select2-multiple" id="ringtone_tags_change"
                                    name="ringtone_tags_change[]" multiple="multiple"
                                    data-placeholder="Choose ..." style="width: 100%">
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <div>
                                <button type="submit" id="saveBtn{{preg_replace('/\s+/','',$page_title)}}ChangeTags" class="btn btn-danger waves-effect waves-light mr-1">
                                    Delete
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
{{--    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>--}}

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

    <script>

        $(function () {
            $(".select2").select2({});

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
                    url: "{{route('tags.getIndex')}}",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    { data: 'tag_name',  className: "align-middle"},
                    { data: 'music_count',className: "align-middle"},
                    { data: 'action',className: "align-middle text-center"}
                ],
                order: [0, 'asc'],
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



            $('#createtags').click(function () {
                $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Add {{$page_title}}");
                $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("create");
                $('#id').val('');
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
            });


            $('#form{{preg_replace('/\s+/','',$page_title)}}').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}")[0]);
                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val() == 'create') {
                    $.ajax({
                        data: formData,
                        url: '{{route('tags.create')}}',
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
                        url: '{{route('tags.update')}}',
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
                var name = $(this).data("name");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/tags/change-tag") }}/"+id,
                    success: function (data) {
                        $('#modal{{preg_replace('/\s+/','',$page_title)}}ChangeTags').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}ChangeTagsModalLabel').html("Delete " + name) ;
                        $(".select2").val('').trigger('change');

                        var html = '';
                        $.each(data.tags, function(i, item) {
                            html += '<option value="'+item.id+'">'+item.tag_name+'</option>'
                        });

                        $('#id_change_tags').val(id);
                        $('#ringtone_tags_change').append(html);
                        $('#wallpaper_tags_change').append(html);


                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('#form{{preg_replace('/\s+/','',$page_title)}}ChangeTags').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}ChangeTags")[0]);

                    $.ajax({
                        data: formData,
                        url: '{{route('tags.delete')}}',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                $('#modal{{preg_replace('/\s+/','',$page_title)}}ChangeTags').modal('hide');
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



            $(document).on('click','.edit{{preg_replace('/\s+/','',$page_title)}}', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/tags/edit") }}/"+id,
                    success: function (data) {

                        $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Edit {{$page_title}}");
                        $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("update");

                        $('#id').val(data.tag.id);
                        $('#tag_name').val(data.tag.tag_name);


                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

        })
    </script>

@endsection
