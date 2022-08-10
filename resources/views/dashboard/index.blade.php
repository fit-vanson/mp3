@extends('layouts.master')

@section('title') @lang('translation.Dashboard') @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/chartist/chartist.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')


    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/01.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Sites</h5>
                        <h4 class="font-weight-medium font-size-24">{{count($sites)}}</h4>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/02.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Tags</h5>
                        <h4 class="font-weight-medium font-size-24">{{$tags}}</h4>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card mini-stat bg-primary text-white">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="float-left mini-stat-img mr-4">
                            <img src="{{ URL::asset('/assets/images/services-icon/03.png') }}" alt="">
                        </div>
                        <h5 class="font-size-16 text-uppercase mt-0 text-white-50">Wallpapers</h5>
                        <h4 class="font-weight-medium font-size-24">{{$wallpapers}}</h4>

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
                    <div class="form-group col-3">
                        <select  class="select2 form-control select2-multiple" id="select_sites"
                                 name="select_sites[]" multiple="multiple"
                                 data-placeholder="Choose ..."
                                 onchange="myFunction()"
                        >
                            @foreach($sites as $site)
                                <option value="{{$site->id}}">{{$site->site_web}}</option>
                            @endforeach
                        </select>
                    </div>

{{--                    <div class="row justify-content-center">--}}
{{--                        <div class="col-sm-4">--}}
{{--                            <div class="text-center">--}}
{{--                                <h5 class="mb-0 font-size-20">86541</h5>--}}
{{--                                <p class="text-muted">Activated</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-4">--}}
{{--                            <div class="text-center">--}}
{{--                                <h5 class="mb-0 font-size-20">2541</h5>--}}
{{--                                <p class="text-muted">Pending</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-4">--}}
{{--                            <div class="text-center">--}}
{{--                                <h5 class="mb-0 font-size-20">102030</h5>--}}
{{--                                <p class="text-muted">Deactivated</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <canvas id="lineChart" height="500"></canvas>

                </div>
            </div>
        </div> <!-- end col -->

{{--        <div class="col-xl-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}

{{--                    <h4 class="card-title mb-4">Line chart</h4>--}}
{{--                    <div class="form-group col-3">--}}
{{--                        <select  class="select2 form-control select2-multiple" id="select_sites"--}}
{{--                                name="select_sites[]" multiple="multiple"--}}
{{--                                data-placeholder="Choose ..."--}}
{{--                                 onchange="myFunction()"--}}
{{--                        >--}}
{{--                            @foreach($sites as $site)--}}
{{--                                <option value="{{$site->id}}">{{$site->site_web}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}

{{--                    <div class="row justify-content-center">--}}
{{--                        <div class="col-sm-4">--}}
{{--                            <div class="text-center">--}}
{{--                                <h5 class="mb-0 font-size-20">44242</h5>--}}
{{--                                <p class="text-muted">Activated</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-4">--}}
{{--                            <div class="text-center">--}}
{{--                                <h5 class="mb-0 font-size-20">75221</h5>--}}
{{--                                <p class="text-muted">Pending</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-4">--}}
{{--                            <div class="text-center">--}}
{{--                                <h5 class="mb-0 font-size-20">65212</h5>--}}
{{--                                <p class="text-muted">Deactivated</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div id="simple-line-chart" class="ct-chart ct-golden-section" dir="ltr"></div>--}}
{{--                    <div id="morris-line-example" class="morris-charts morris-chart-height" dir="ltr"></div>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div> <!-- end col -->--}}



    </div>
    <!-- end row -->

@endsection

@section('script')
    <!-- Peity chart-->
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <!-- Plugin Js-->
{{--    <script src="{{ URL::asset('/assets/libs/chartist/chartist.min.js') }}"></script>--}}
    <!-- plugin js -->
    <script src="{{ URL::asset('/assets/libs/chart-js/chart-js.min.js') }}"></script>

    <!-- demo js -->
{{--    <script src="{{ URL::asset('/assets/js/pages/chartjs.init.js') }}"></script>--}}

    <!-- demo js -->
{{--    <script src="{{ URL::asset('/assets/js/pages/morris.init.js') }}"></script>--}}


    <script>
        $(".select2").select2({});



        function loaddata(id){
            $.ajax({
                type: "get",
                url: "{{route('home.load_data')}}?id="+id,
                success: function(result) {


                    console.log(result);


                    !function ($) {
                        "use strict";

                        var ChartJs = function ChartJs() {};

                        ChartJs.prototype.respChart = function (selector, type, data, options) {
                            Chart.defaults.global.defaultFontColor = "#adb5bd", Chart.defaults.scale.gridLines.color = "rgba(108, 120, 151, 0.1)"; // get selector by context

                            var ctx = selector.get(0).getContext("2d"); // pointing parent container to make chart js inherit its width

                            var container = $(selector).parent(); // enable resizing matter

                            $(window).resize(generateChart); // this function produce the responsive Chart JS

                            function generateChart() {
                                // make chart width fit with its container
                                var ww = selector.attr('width', $(container).width());

                                switch (type) {
                                    case 'Line':
                                        new Chart(ctx, {
                                            type: 'line',
                                            data: data,
                                            options: options
                                        });
                                        break;
                                } // Initiate new chart or Redraw

                            }; // run function - render chart at first load

                            generateChart();
                        }, //init
                            ChartJs.prototype.init = function () {
                                //creating lineChart
                                var lineChart = {
                                    labels: result.labels,
                                    datasets: result.datasets
                                };
                                var lineOpts = {
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                // max: 100,
                                                // min: 20,
                                                stepSize: 10
                                            }
                                        }]
                                    }
                                };
                                this.respChart($("#lineChart"), 'Line', lineChart, lineOpts); //donut chart
                            }, $.ChartJs = new ChartJs(), $.ChartJs.Constructor = ChartJs;
                    }(window.jQuery),
                        function ($) {
                            "use strict";
                            $.ChartJs.init();
                        }(window.jQuery);





                }



            });
        }

        function myFunction() {
            $("#first").val();
            var id = $("#select_sites").val();
            loaddata(id)

        }

        loaddata(); // This will run on page load





    </script>
@endsection
