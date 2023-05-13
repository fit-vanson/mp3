@extends('layouts.master')
<?php
$page_title = $header['title'];
$button = $header['button'];
?>
@section('title') {{$page_title}}  @endsection


@section('css')
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
    <!-- DataTables -->
    <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/datatables/autoFill.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/datatables/keyTable.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- x-editable -->
    <link href="{{ URL::asset('plugins/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('content')

    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-1">
                        <table  id="GoogleAdsTable" class="table table-editable table-bordered dt-responsive">
                            <thead>
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 30%">Name</th>
                                <th style="width: 5%">Devices</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


    <!--  Modal content for the above example -->
    <div class="modal fade" id="modalGoogleAdsEdit" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalGoogleAdsLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="card-body">
                    <form id="formGoogleAdsEdit">
                        <input type="hidden" name="GoogleAds_id" id="GoogleAds_id">

                        <div class="form-group">
                            <label>URL</label>
                            <input type="text" class="form-control" id="GoogleAds_name" name="GoogleAds_name" >
                        </div>

                        <div class="form-group">
                            <label>URL Block <code> full link, nếu để trống thì chính trang direct</code></label>
                            <input type="text" class="form-control" id="GoogleAds_url_block" name="GoogleAds_url_block" >
                        </div>

                        <div class="form-group">
                            <label>HTML</label>
                            <textarea class="form-control" id="GoogleAds_html" name="GoogleAds_html" rows="10"></textarea>
                        </div>


                        <div class="form-group">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="is_Devices" name="GoogleAds_is_Devices" class="custom-control-input" checked value="0">
                                <label class="custom-control-label" for="is_Devices">Thiết bị</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="is_Country" name="GoogleAds_is_Devices" class="custom-control-input" value="1">
                                <label class="custom-control-label" for="is_Country">Quốc gia</label>
                            </div>
                        </div>


                        <div class="is_Country">
                            <div class="form-group repeater">
                                <div data-repeater-list="GoogleAds_country">
                                    <div data-repeater-item class="row">
                                        <div  class="form-group col-lg-4">
                                            <input type="text" id="GoogleAds_country" name="country" class="form-control" placeholder="Quốc gia" />
                                        </div>

                                        <div  class="form-group col-lg-6">
                                            <input type="text" id="GoogleAds_url" name="url" class="form-control" placeholder="URL"/>
                                        </div>
                                        <div class="form-group col-lg-2 align-self-center">
                                            <input data-repeater-delete type="button" class="btn btn-danger btn-block" value="Delete"/>
                                        </div>
                                    </div>
                                </div>
                                <input data-repeater-create type="button" class="btn btn-success mo-mt-2" value="Add"/>
                            </div>
                        </div>

                        <div class="is_Devices">
                            <div class="form-group ">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Android</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" id="GoogleAds_Devices_Android" name="GoogleAds_Devices['Android']">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label for="example-number-input" class="col-sm-2 col-form-label">OS</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" id="GoogleAds_Devices_OS" name="GoogleAds_Devices['OS']">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <label for="example-number-input" class="col-sm-2 col-form-label">Windows</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" id="GoogleAds_Devices_Windows" name="GoogleAds_Devices['Windows']">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <label for="example-number-input" class="col-sm-2 col-form-label">Other</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" id="GoogleAds_Devices_Other" name="GoogleAds_Devices['Other']">
                                    </div>
                                </div>
                            </div>
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



@endsection

@section('script')
    <!-- Plugins js -->
    <!-- Required datatable js -->
    <script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/autoFill.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/x-editable/js/bootstrap-editable.min.js') }}"></script>

    <script src="{{ URL::asset('plugins/jquery-repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ URL::asset('assets/pages/form-repeater.int.js') }}"></script>


    <script>

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.is_Devices').hide();
            $('.is_Country').hide();
            $('input:radio[name="GoogleAds_is_Devices"]').change(function () {
                if ($(this).val() == '0') {
                    $('.is_Devices').show();
                    $('.is_Country').hide();
                } else {
                    $('.is_Devices').hide();
                    $('.is_Country').show();
                }
            });



            const GoogleAdsTable = $('#GoogleAdsTable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('google_ads.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'is_Devices'},
                    {data: 'action'},
                ],
                order: [0, 'desc'],


                drawCallback: function (settings) {
                    $.fn.editable.defaults.mode = 'inline';

                    $('.editable').editable({
                        success: function (data, newValue) {
                            var _id = $(this).data('pk')
                            $.ajax({
                                url: "{{ route("google_ads.update") }}?id=" + _id + '&value=' + newValue,
                                responseTime: 400,
                                success: function (result) {
                                    if (result.success) {
                                        toastr['success'](result.success, 'Success!');
                                    }
                                    if (result.error) {
                                        toastr['error'](result.error, 'Error!',);
                                    }
                                }
                            });
                        },
                    });
                },
            });

            $(document).on('click', '#createGoogle_Ads', function () {
                $.ajax({
                    type: "post",
                    url: "{{ route("google_ads.create") }}",
                    success: function (data) {
                        if(data.success){
                            toastr['success'](data.success, 'Success!');
                            $('#GoogleAdsTable').DataTable().draw();
                        }
                        if(data.error){
                            toastr['error'](data.error, 'Error!',);
                        }

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });


            $(document).on('click', '.editGoogle_ads', function () {
                $('#modalGoogleAdsEdit').modal('show');
                $('#modalGoogleAdsLabel').html('Edit Google Ads');

                const GoogleAds_id =  $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ route('google_ads.edit', ':id') }}".replace(':id', GoogleAds_id),
                    success: function (data) {
                        $('#GoogleAds_name').val(data.name);
                        $('#GoogleAds_id').val(GoogleAds_id);
                        $('#GoogleAds_url_block').val(data.url_block);
                        $('#GoogleAds_html').val(data.html);








                        if(data.is_Devices == 0){
                            $('#is_Devices').prop('checked', true);
                            $('.is_Devices').show();
                            $('.is_Country').hide();
                        }else {
                            $('#is_Country').prop('checked', true);
                            $('.is_Devices').hide();
                            $('.is_Country').show();
                        }

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('#formGoogleAdsEdit').on('submit', function (event) {
                event.preventDefault();
                var formData = new FormData($("#formGoogleAdsEdit")[0]);
                    $.ajax({
                        data: formData,
                        url: '{{route('google_ads.updatePost')}}',
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                $('#formGoogleAdsEdit').trigger("reset");
                                toastr['success'](data.success, 'Success!');
                                $('#modalGoogleAdsEdit').modal('hide');
                                $('#GoogleAdsTable').DataTable().draw();

                            }
                            if (data.errors) {
                                for (var count = 0; count < data.errors.length; count++) {
                                    toastr['error'](data.errors[count], 'Error!',);
                                }
                            }
                        }
                    });

            });

        })
    </script>



@endsection
