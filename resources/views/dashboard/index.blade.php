@extends('layouts.master')

@section('title') @lang('translation.Dashboard') @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/chartist/chartist.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')


    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/01.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Sites</h5>
                        <span class="font-weight-medium font-size-24">{{count($sites)}}</span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/02.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Tags</h5>
                        <span class="font-weight-medium font-size-24">{{$tags}}</span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/06.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Wallpapers</h5>
                        <span class="font-weight-medium font-size-24">{{count($wallpapers)}} /  </span>
                        ( <span class="font-weight-medium" style="color: red">{{count($wallpapers->where('wallpaper_status',0))}}</span> )

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/05.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Ringtones</h5>
                        <span class="font-weight-medium font-size-24">{{count($ringtones)}}  </span>
{{--                        ( <span class="font-weight-medium" style="color: red">{{count($wallpapers->where('wallpaper_status',0))}}</span> )--}}

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end row -->

    <div class="row">

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
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    // ticks: {
                                    //     // max: 100,
                                    //     // min: 20,
                                    //     stepSize: 10
                                    // }
                                }]
                            }

                        }
                    });
                }
            });
        }

        // function myFunction() {
        //     $("#first").val();
        //     var id = $("#select_sites").val();
        //     loaddata(id)
        // }

        loaddata(); // This will run on page load
    </script>
@endsection
