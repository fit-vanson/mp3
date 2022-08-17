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

        function loaddata(id){
            $.ajax({
                type: "get",
                url: "{{route('home.load_data')}}?id="+id,
                success: function(result) {
                    const myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: result.labels,
                            datasets: result.datasets
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        // max: 100,
                                        // min: 20,
                                        stepSize: 10
                                    }
                                }]
                            }

                        }
                    });
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