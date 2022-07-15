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
                                    <th style="width: 10%">Image Count</th>
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
                        <input  id="image" type="file" name="image" class="form-control" hidden accept="image/*" onchange="changeImg(this)">
                        <img id="avatar" width="200px" src="{{asset('assets/images/1.png')}}">
                        <div class="form-group">
                            <label>Category name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>

                        <div class="form-group">
                            <label>Order</label>
                            <input type="text" class="form-control" id="category_order" name="category_order"  value="{{rand(0,1)}}" >
                        </div>

                        <div class="form-group">
                            <label>View Count</label>
                            <input type="text" class="form-control" id="category_view_count" name="category_view_count"  value="{{rand(500,3000)}}" >
                        </div>


                        <div class="form-group">
                            <input type="checkbox" id="category_checked_ip" name="category_checked_ip"  switch="none" checked="">
                            <label for="category_checked_ip" data-on-label="Real" data-off-label="Fake"></label>
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
            $('.createTags').hide()

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
                    { data: 'tag_name',  className: "align-middle", },
                    { data: 'wallpaper_count',className: "align-middle",},
                    { data: 'action',className: "align-middle text-center ", }
                ],
                order: [1, 'asc'],

            });


            $(document).on('click','.delete{{preg_replace('/\s+/','',$page_title)}}', function (data){
                var id = $(this).data("id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "Yes, delete it!"
                }).then(function (result) {

                    $.ajax({
                        type: "get",
                        url: "{{ asset("tags/delete") }}/"+id,
                        success: function (data) {
                            toastr['success'](data.success, 'Success!');
                            dtTable.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });
            });

        })
    </script>

@endsection
