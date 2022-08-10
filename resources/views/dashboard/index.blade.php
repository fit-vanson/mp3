@extends('layouts.master')

@section('title') @lang('translation.Dashboard') @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/chartist/chartist.min.css') }}" rel="stylesheet" type="text/css" />
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
                        <h4 class="font-weight-medium font-size-24">{{$sites}}</h4>

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

                    <h4 class="card-title mb-4">Line chart</h4>

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

                    <div id="simple-line-chart" class="ct-chart ct-golden-section" dir="ltr"></div>

                </div>
            </div>
        </div> <!-- end col -->


    </div>
    <!-- end row -->

@endsection

@section('script')
    <!-- Peity chart-->
{{--    <script src="{{ URL::asset('/assets/libs/peity/peity.min.js') }}"></script>--}}

    <!-- Plugin Js-->
    <script src="{{ URL::asset('/assets/libs/chartist/chartist.min.js') }}"></script>


    <script>


        function loaddata(){
            $.ajax({
                type: "get",
                url: "{{route('home.load_data')}}",
                success: function(result) {
                    new Chartist.Line('#simple-line-chart', {
                        labels: result.date,
                        series: [result.data]
                    }, {
                        fullWidth: true,
                        chartPadding: {
                            right: 40
                        },
                        plugins: [Chartist.plugins.tooltip()]
                    }); //Line Scatter Diagram
                },
            });
        }

        loaddata(); // This will run on page load





    </script>
@endsection
