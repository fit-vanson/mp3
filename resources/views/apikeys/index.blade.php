@extends('layouts.master')

@section('title') {{$page_title}}  @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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
                                    <th style="width: 50%">Key</th>
                                    <th style="width: 10%">Active</th>

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
                            <label>Name</label>
                            <input type="text" class="form-control" id="apikey_name" name="apikey_name" required>
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
    <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

    <script>
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
                    url: "{{route('apikeys.getIndex')}}",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    { data: 'name',  className: "align-middle", },
                    { data: 'key',className: "align-middle",},
                    { data: 'active',className: "align-middle",},

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



            $('.create{{preg_replace('/\s+/','',$page_title)}}').click(function () {
                $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Add {{$page_title}}");
                $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("create");
                $('#id').val('');
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
            });

            $('#form{{preg_replace('/\s+/','',$page_title)}}').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#form{{preg_replace('/\s+/','',$page_title)}}")[0]);

                $.ajax({
                    data: formData,
                    url: '{{route('apikeys.create')}}',
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

            $(document).on('click','.changeStatus', function (data){
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/api-keys/change-status") }}/"+id,

                    success: function (data) {
                        dtTable.draw();
                        toastr['success']('', data.success, {
                            showMethod: 'fadeIn',
                            hideMethod: 'fadeOut',
                            timeOut: 2000,
                        });
                    },
                    error: function (data) {
                    }
                });
            });




        })
    </script>

@endsection
