@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Role Management</h3>
            <ul class="breadcrumbs mb-3">
             
               
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="AddMenuForm" enctype="multipart/form-data" method="POST">
                            {{-- @csrf --}}
                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="rolename" style="font-weight:normal">Role Name</label>
                                        <input type="text" class="form-control w-75" id="rolename"
                                            name="rolename" placeholder="e.g. Manager">
                                        <h6 class="text-danger pt-1" id="wrongrolename" style="font-size: 14px;">
                                        </h6>

                                    </div>

                                    <div class="form-check ml-1">
                                        <input type="checkbox" style="width: 16px;height: 16px;"
                                            class="form-check-input" id="checkPermissionAll" value="1">
                                        <label class="form-check-label" for="checkPermissionAll">All
                                            Permissions</label>
                                    </div>
                                </div>

                            </div>


                            {{-- <hr> --}}
                            @php $i = 1; @endphp


                            @foreach ($permission_groups as $group)
                                <div id="div{{ $i }}">
                                    <div class="form-group float-left pt-3" style="margin-left: 20px">
                                        <input type="checkbox" class="form-check-input"
                                            style="border: 1px solid #ccc; padding: 5px;"
                                            id="checkPermissionAll_group{{ $i }}"
                                            onclick="checkCheckboxes(this.id, 'div{{ $i }}');">
                                        <label class="form-check-label"
                                            for="checkPermissionAll_group{{ $i }}"
                                            style="font-size:17px;">
                                            <b> {{ $group->name }}</b>
                                        </label>
                                    </div>
                                    @php $i++; @endphp
                                    {{-- <input type="checkbox" class="form-check-input"
                                    style="width: 18px; height: 21px;" id="{{ $i }}Management"
                                    value="{{ $group->name }}"
                                    onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)">

                                <h4> {{$group->name}}</h4> --}}

                                    @php
                                        $permissions = App\Models\User::getpermissionsByPermissionName(
                                            $group->name,
                                        );
                                        $j = 1;
                                    @endphp
                                    <hr>



                                    <table class="table table-bordered" id="class_table" width="100%"
                                        cellspacing="0">

                                        <tr class="table-info">
                                            <th>Sl</th>
                                            <th>Permission Name</th>

                                            <th>Create</th>
                                            <th>Read</th>
                                            <th>Update</th>
                                            <th>Delete</th>
                                        </tr>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>
                                                    @php
                                                        echo $j;
                                                    @endphp
                                                </td>

                                                <td>

                                                    <label
                                                        class="form-check-label">{{ $permission->permissions_name }}
                                                    </label>

                                                </td>

                                                <td>
                                                    @if ($contains = Illuminate\Support\Str::contains($permission->p_create, 'create'))
                                                        {{-- @if ($permission->permission_type == 'create') --}}
                                                        <label class="form-check-label" for="checkPermission">
                                                            {!! '&emsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;' !!}</label>
                                                        <input type="checkbox" class="form-check-input"
                                                            style=" border: 1px solid #ccc; padding: 5px;"name="permissions[]"
                                                            id="checkPermission{{ $permission->p_create_id }}"
                                                            value="{{ $permission->p_create }}">
                                                    @else
                                                        {{ 'N/A' }}
                                                    @endif
                                                </td>




                                                <td>
                                                    @if ($contains = Illuminate\Support\Str::contains($permission->p_view, 'view'))
                                                        {{-- @if ($permission->permission_type == 'view') --}}
                                                        <label class="form-check-label" for="checkPermission">
                                                            {!! '&emsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;' !!}</label>
                                                        <input type="checkbox" class="form-check-input"
                                                            style=" border: 1px solid #ccc; padding: 5px;"name="permissions[]"
                                                            id="checkPermission{{ $permission->p_view_id }}"
                                                            value="{{ $permission->p_view }}">
                                                    @else
                                                        {{ 'N/A' }}
                                                    @endif
                                                </td>



                                                <td>
                                                    @if ($contains = Illuminate\Support\Str::contains($permission->p_edit, 'edit'))
                                                        {{-- @if ($permission->permission_type == 'edit') --}}
                                                        <label class="form-check-label" for="checkPermission">
                                                            {!! '&emsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;' !!}</label>
                                                        <input type="checkbox" class="form-check-input"
                                                            style=" border: 1px solid #ccc;padding: 5px;"name="permissions[]"
                                                            id="checkPermission{{ $permission->p_edit_id }}"
                                                            value="{{ $permission->p_edit }}">
                                                    @else
                                                        {{ 'N/A' }}
                                                    @endif

                                                </td>



                                                <td>
                                                    @if ($contains = Illuminate\Support\Str::contains($permission->p_destroy, 'destroy'))
                                                        {{-- @if ($permission->permission_type == 'destroy') --}}
                                                        <label class="form-check-label" for="checkPermission">
                                                            {!! '&emsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;';
                                                            '&nbsp;' !!}</label>
                                                        <input type="checkbox" class="form-check-input"
                                                            style=" border: 1px solid #ccc; padding: 5px;"name="permissions[]"
                                                            id="checkPermission{{ $permission->p_destroy_id }}"
                                                            value="{{ $permission->p_destroy }}">
                                                    @else
                                                        {{ 'N/A' }}
                                                    @endif
                                                </td>


                                            </tr>
                                            @php $j++; @endphp
                                        @endforeach
                                    </table>



                                    <br>
                                </div>
                            @endforeach

                            <button id="save" type="submit" onclick=""
                                class="btn btn-primary mt-4">Save</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $("#checkPermissionAll").click(function() {
        if ($(this).is(":checked")) {
            // check all the checkbox
            $("input[type=checkbox]").prop("checked", true);
        } else {
            // uncheck all the checkbox
            $("input[type=checkbox]").prop("checked", false);
        }
    });

    function checkCheckboxes(checkbox_id, div_id) {

        $(function() {
            if ($('input#' + checkbox_id).is(":checked")) {
                $('#' + div_id + ' input:checkbox').prop("checked", true);
            } else {

                $('#' + div_id + ' input:checkbox').prop("checked", false);

            }
        });

    }


    $(document).ready(function() {
        //CREATE BATCH
        $(document).on('submit', '#AddMenuForm', function(e) {
            e.preventDefault();

            let formData = new FormData($('#AddMenuForm')[0]);

            $.ajax({
                type: "POST",
                url: "/roles-create",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // alert(response.message);	
                    if ($.isEmptyObject(response.error)) {

                        $(location).attr('href', '/role-list');

                    } else {
                        printErrorMsg(response.error);
                    }
                }
            });
        });

        function printErrorMsg(message) {

            $('#wrongrolename').empty();

            if (message.rolename == null) {
                rolename = ""
            } else {
                rolename = message.rolename[0]
            }

            // $('#save').notify(rolename, {
            //     className: 'error',
            //     position: 'right'
            // })
            $.notify({
                        // Adding icon and message
                        icon: 'fa fa-bell',
                        message: rolename,
                    }, {

                        type: 'error    ',
                        placement: {
                            from: "top",
                            align: 'right',
                        },
                    });
            $('#wrongrolename').append('<span id="">' + rolename + '</span>');

            // });
        }
    });
</script>

{{-- 
<script>
    $(document).ready(function() {



        var action =
            '<td> <div class="form-button-action"> 
                <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> 
                <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

   
    });
</script> --}}
@endsection
