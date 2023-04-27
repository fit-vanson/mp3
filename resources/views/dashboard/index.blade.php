@extends('layouts.master')

@section('title') @lang('translation.Dashboard') @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/chartist/chartist.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')


    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/01.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Sites</h5>
                        <span class="font-weight-medium font-size-24">{{number_format(count($sites))}}</span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/02.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Tags</h5>
                        <span class="font-weight-medium font-size-24">{{number_format($tags)}}</span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/05.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Music</h5>
                        <span class="font-weight-medium font-size-24">{{number_format(count($musics))}}  </span>

                    </div>
                </div>
            </div>
        </div>

{{--        <div class="col-lg-3 col-md-6">--}}
{{--            <div class="card mini-stat bg-primary text-white">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="mb-4">--}}
{{--                        <div class="float-left mini-stat-img mr-4">--}}
{{--                            <img src="{{ URL::asset('/assets/images/services-icon/05.png') }}" alt="">--}}
{{--                        </div>--}}
{{--                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Ringtones</h5>--}}
{{--                        <span class="font-weight-medium font-size-24">{{number_format(count($ringtones))}}  </span>--}}
{{--                        ( <span class="font-weight-medium" style="color: red">{{count($wallpapers->where('wallpaper_status',0))}}</span> )--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>
    <!-- end row -->

    <div class="row">

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Best App</h4>
                    <div class="table-responsive">
                        <table class="table table-hover mostappTable" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 60%" scope="col">Site</th>
                                <th style="width: 10%"scope="col">Today</th>
                                <th style="width: 10%" scope="col">Last Day</th>
                                <th style="width: 10%" scope="col">In 7 Day</th>
                                <th style="width: 10%" scope="col">In Month</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Country</h4>
                    <div class="table-responsive">
                        <table class="table table-hover mostcountryTable" style="width: 100%;">
                            <thead>
                            <tr>

                                <th style="width: 50%;" scope="col">Country</th>
                                <th style="width: 50%;" scope="col">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-4">Line Chart</h4>
                    <canvas id="lineChart"  width="400" height="100"></canvas>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

@endsection

@section('script')
    <!-- Peity chart-->
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <!-- plugin js -->
    <script src="{{ URL::asset('/assets/libs/chart-js/chart-js.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>


    <script>
        $(".select2").select2({});
        const ctx = document.getElementById('lineChart').getContext('2d');

        function loaddata(){
            $.ajax({
                type: "get",
                url: "{{route('home.load_data')}}",
                success: function(result) {
                    const myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: result.labels,
                            datasets: result.datasets
                        },

                        options: {
                            // legend: {
                            //     display: false
                            // },
                            scales: {
                                yAxes: [{
                                    id: 'A',
                                    type: 'linear',
                                    position: 'left',
                                }, {
                                    id: 'B',
                                    type: 'linear',
                                    position: 'right',

                                }]
                            }

                        }
                    });
                }
            });
        }
        loaddata();

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var dtTable = $('.mostappTable').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                bInfo : false,
                searching: false,
                ordering: false,
                ajax: {
                    url: "{{route('home.load_mostApp')}}",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    {data: 'logo',},
                    {data: 'count_today'},
                    {data: 'count_lastday'},
                    {data: 'count_7day'},
                    {data: 'count_month'},
                ],
                // order: [1, 'asc'],

                // fnDrawCallback: function () {
                //     $('.image-popup-no-margins').magnificPopup({
                //         type: 'image',
                //         closeOnContentClick: true,
                //         closeBtnInside: false,
                //         fixedContentPos: true,
                //         mainClass: 'mfp-no-margins mfp-with-zoom',
                //         image: {
                //             verticalFit: true
                //         },
                //         zoom: {
                //             enabled: true,
                //             duration: 300 // don't foget to change the duration also in CSS
                //
                //         }
                //     });
                // }

            });

            var dtTable = $('.mostcountryTable').DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                bInfo : false,
                searching: false,
                ordering: false,
                ajax: {
                    url: "{{route('home.load_mostCountry')}}",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    {data: 'country'},
                    {data: 'count_today'},
                ],
                // order: [1, 'asc'],

                // fnDrawCallback: function () {
                //     $('.image-popup-no-margins').magnificPopup({
                //         type: 'image',
                //         closeOnContentClick: true,
                //         closeBtnInside: false,
                //         fixedContentPos: true,
                //         mainClass: 'mfp-no-margins mfp-with-zoom',
                //         image: {
                //             verticalFit: true
                //         },
                //         zoom: {
                //             enabled: true,
                //             duration: 300 // don't foget to change the duration also in CSS
                //
                //         }
                //     });
                // }

            });
        })
    </script>
@endsection
