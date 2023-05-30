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
                            <table id="GoogleAdsDetailsTable" class="table table-bordered dt-responsive"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 10%">ip_address</th>
                                    <th style="width: 55%">device_name</th>
                                    <th style="width: 15%">country</th>
                                    <th style="width: 15%">updated_at</th>
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





@endsection

@section('script')
    <!-- Plugins js -->
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>




    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function getQueryParam(param) {
                var urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }
            var url ="";
            var googleAdsId = getQueryParam('googleAds_id');
            if(googleAdsId){
                url = "{{ route("google_ads.getIndexDetail") }}?googleAds_id=" + googleAdsId
            }else {
                url = "{{ route("google_ads.getIndexDetail") }}"
            }

            $('#GoogleAdsDetailsTable').dataTable({

                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: "post"
                },
                columns: [
                    {data: 'id'},
                    {data: 'ip_address'},
                    {data: 'device_name'},
                    {data: 'country'},
                    {data: 'updated_at'},
                ],
                order: [4, 'desc'],
            });

            $(document).on('click', '#clearIPGoogle_Ads', function () {
                $.ajax({
                    type: "get",
                    url: "{{ route('google_ads.clearIP', ':id') }}".replace(':id', googleAdsId),
                    success: function (data) {
                        if(data.success){
                            toastr['success'](data.success, 'Success!');
                            $('#GoogleAdsDetailsTable').DataTable().draw();
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

        })
    </script>


@endsection
