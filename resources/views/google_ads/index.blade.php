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
                                <th style="width: 20%">Name</th>
                                <th>URL</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->



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

    <script>

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var GoogleAdsTable = $('#GoogleAdsTable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('google_ads.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'value'},
                ],


                drawCallback: function (settings) {
                    $.fn.editable.defaults.mode = 'inline';

                    $('.editable').editable({
                        success:function(data,newValue){
                            var _id = $(this).data('pk')
                            $.ajax({
                                url: "{{ route("google_ads.update") }}?id=" + _id+'&value='+newValue,
                                responseTime: 400,
                                success: function (result) {
                                    console.log(result)
                                    if(result.success){
                                        toastr['success'](result.success, 'Success!');
                                    }
                                    if(result.error){
                                        toastr['error'](result.error, 'Error!',);
                                    }
                                }
                            });
                        } ,
                    });
                },
            });

        })
    </script>



@endsection
