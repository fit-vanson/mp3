@extends('layouts.master')

@section('title')  Roles - Permissions @endsection

@section('css')
    <!-- datatables css -->
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

                    <table id="table{{preg_replace('/\s+/','',$page_title)}}" class="table table-bordered dt-responsive"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Role</th>
                                <th>Users</th>
                                <th>Permission</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>

                        </tbody>
                    </table>

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
{{--                        @csrf--}}
                        <div class="form-group">
                            <input type="hidden" name="id" id="id">
                            <label>Role name</label>
                            <input type="text" class="form-control" id="name{{preg_replace('/\s+/','',$page_title)}}" name="name{{preg_replace('/\s+/','',$page_title)}}" required placeholder="{{$page_title}}">
                        </div>
                        <div class="form-group">
                            <label>Role Permissions</label>
                            <div class="table-responsive">
                                <table class="table table-flush-spacing">
                                    <tbody>
                                    <tr>
                                        <td class="text-nowrap fw-bolder">
                                            Administrator Access
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll" />
                                                <label class="form-check-label fw-bolder" for="selectAll"> Select All </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-nowrap fw-bolder">All</td>
                                        <td>
                                            <div class="d-flex">
                                                @foreach(config('permission.module-child') as $item)
                                                    <div class="form-check col-lg-3 col-3 ">
                                                        <input class="form-check-input" type="checkbox" onclick="checkbox('{{$item}}')" id="selectAll_{{$item}}" value="{{$item}}"/>
                                                        <label class="form-check-label fw-bolder" for="userManagement{{$item}}"> All {{$item}} </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>

                                    @foreach(config('permission.table-module') as $table)
                                        <tr>
                                            <td class="text-nowrap fw-bolder">{{$table}}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @foreach(config('permission.module-child') as $item)
                                                        <div class="form-check col-lg-3 col-3">
                                                            <input class="form-check-input class_{{$item}}" type="checkbox" id="{{preg_replace('/\s+/','',$item.$table)}}" name="userManagement[]" value="{{preg_replace('/\s+/','',$item.$table)}}"/>
                                                            <label class="form-check-label" for="userManagement{{$item}}"> {{$item}} </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
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
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

    <script>
        function checkbox(e) {
            const selectAll = document.querySelector('#selectAll_' + e),
                checkboxList = document.querySelectorAll('.class_' + e);
            selectAll.addEventListener('change', t => {
                checkboxList.forEach(e => {
                    e.checked = t.target.checked;
                });
            });
        }
        // Select All checkbox click
        const selectAll = document.querySelector('#selectAll'),
            checkboxList = document.querySelectorAll('[type="checkbox"]');
        selectAll.addEventListener('change', t => {
            checkboxList.forEach(e => {
                e.checked = t.target.checked;
            });
        });
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
                    url: "{{route('roles_permissions.getIndex')}}",
                    type: "post"
                },
                columns: [
                    // columns according to JSON
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'users' },
                    { data: 'permissions' },
                    { data: 'action' }
                ],
                columnDefs: [
                    {
                        targets: 2,
                        responsivePriority: 1,
                        render: function (data) {
                            var users = data,
                                $output = '';
                            $.each(users, function(i, item) {
                                $output += ' <span style="margin-top: 5px;" class="badge badge-success">' + item.name + '</span> ';
                                return i<2;
                            });
                            return $output
                        }
                    },
                    {
                        targets: 3,
                        responsivePriority: 1,
                        render: function (data) {
                            var permissions = data,
                                $output = '';
                            $.each(permissions, function(i, item) {
                              $output += ' <span style="margin-top: 5px;" class="badge badge-primary">' + item.name + '</span> ';
                              return i<2;
                            });
                            return $output
                        }
                    },

                ],
                order: [1, 'asc'],


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
                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val() == 'create') {
                    $.ajax({
                        data: formData,
                        url: '{{route('roles_permissions.create')}}',
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
                }
                if ($('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val() == 'update') {
                    $.ajax({
                        data: formData,
                        url: '{{route('roles_permissions.update')}}',
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
                }

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
                        url: "{{ asset("admin/roles-permissions/delete") }}/"+id,
                        success: function (data) {
                            toastr['success'](data.success, 'Success!');
                            dtTable.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                    // Swal.fire("Deleted!", "Your file has been deleted.", "success");


                    // if (result.value) {
                    //     Swal.fire("Deleted!", "Your file has been deleted.", "success");
                    // }
                });



                {{--swal({--}}
                {{--        title: "Bạn có chắc muốn xóa?",--}}
                {{--        text: "Your will not be able to recover this imaginary file!",--}}
                {{--        type: "warning",--}}
                {{--        showCancelButton: true,--}}
                {{--        confirmButtonClass: "btn-danger",--}}
                {{--        confirmButtonText: "Xác nhận xóa!",--}}
                {{--        closeOnConfirm: false--}}
                {{--    },--}}
                {{--    function(){--}}
                {{--        $.ajax({--}}
                {{--            type: "get",--}}
                {{--            url: "{{ asset("project/delete") }}/" + project_id,--}}
                {{--            success: function (data) {--}}
                {{--                table.draw();--}}
                {{--            },--}}
                {{--            error: function (data) {--}}
                {{--                console.log('Error:', data);--}}
                {{--            }--}}
                {{--        });--}}
                {{--        swal("Đã xóa!", "Your imaginary file has been deleted.", "success");--}}
                {{--    });--}}
            });

            $(document).on('click','.edit{{preg_replace('/\s+/','',$page_title)}}', function (data) {
                $('#form{{preg_replace('/\s+/','',$page_title)}}').trigger("reset");
                var id = $(this).data("id");
                $.ajax({
                    type: "get",
                    url: "{{ asset("admin/roles-permissions/edit") }}/"+id,
                    success: function (data) {
                        $('#modal{{preg_replace('/\s+/','',$page_title)}}').modal('show');
                        $('#{{preg_replace('/\s+/','',$page_title)}}ModalLabel').html("Edit {{$page_title}}");
                        $('#saveBtn{{preg_replace('/\s+/','',$page_title)}}').val("update");

                        $('#id').val(data.role.id);
                        $('#name{{preg_replace('/\s+/','',$page_title)}}').val(data.role.name);
                        $.each( data.permissions, function( index, value ) {
                            if(value.name){
                                $("#"+value.name).prop('checked', true);
                            }
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });


        })



    </script>

@endsection
