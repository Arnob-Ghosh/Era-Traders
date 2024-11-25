@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">DataTables.Net</h3>
            <ul class="breadcrumbs mb-3">


            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Add Row</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i>
                                Add Row
                            </button>
                        </div>
                    </div>
                    <div class="card-body">


                        <form id="" method="" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="vatname" class="form-label" style="font-weight: normal;">Permission
                                            Name<span class="text-danger"><strong>*</strong></span></label>
                                        <input type="text" class="form-control w-75" name="permission_name"
                                            id="permission_name" placeholder="Enter Permission Name">
                                        <h6 class="text-danger pt-1" id="wrong_permission_name" style="font-size: 14px;">
                                        </h6>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">

                                        <label for="brandname" class="form-label" style="font-weight: normal;">Permission
                                            Group Name<span class="text-danger"><strong>*</strong></span></label><br>
                                        <select style="width:50%" class="form-select " name="permission_group"
                                            id="permission_group" data-live-search="true"
                                            title="Select Permission Group Name" data-width="75%">
                                            @foreach ($permission_groups as $permission_group)
                                                <option value="{{ $permission_group->group_name }}">
                                                    {{ $permission_group->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="vatname" class="form-label" style="font-weight: normal;">Permission
                                            Route Name<span class="text-danger"><strong>*</strong></span></label>
                                        <input type="text" class="form-control w-75" name="route_name" id="route_name"
                                            placeholder="Enter Permission Route Name">
                                        <h6 class="text-danger pt-1" id="wrong_permission_name" style="font-size: 14px;">
                                        </h6>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="vatname" class="form-label" style="font-weight: normal;">Permission
                                            type<span class="text-danger"><strong>*</strong></span></label><br>
                                        <select style="width:50%" class="form-select " name="permission_type"
                                            id="permission_type" data-live-search="true" title="Select Permission Type"
                                            data-width="75%">
                                            <option value="create">Create</option>
                                            <option value="edit">Edit</option>
                                            <option value="view">View</option>
                                            <option value="destroy">Delete</option>
                                        </select>
                                        <h6 class="text-danger pt-1" id="wrong_permission_type" style="font-size: 14px;">
                                        </h6>

                                    </div>
                                </div>
                                <div class="row form-group pt-3">
                                    <div class="col-7"></div>

                                    <div class="col-5">
                                        <button type="reset" value="Reset"
                                            class="btn btn-outline-danger justify-content-end" onclick="resetButton()"><i
                                                class="fas fa-eraser"></i> Reset</button>
                                        <button id="add_btn" type="button"
                                            class=" w-30 btn btn-primary justify-content-end ml-2"
                                            onclick="permissionAddToTable()"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>

                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-10">
                                        <table id="permission_transfer_table"
                                            class="table align-items-center mb-0 text-dark mx-1 my-1">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Route Name</th>

                                                    <th scope="col">Permission Name</th>

                                                    <th scope="col">Permission Group Name</th>

                                                    <th scope="col">Permission Type</th>
                                                </tr>
                                            </thead>
                                            <tbody id="permission_transfer_table_body">


                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                    <div class="col-9">
                                        <h6 class="text-danger text-end"><strong id="errorMsg1"></strong></h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-10 text-end" style="padding-top: 10px">
                                        <button id="" type="button" class=" btn btn-primary "
                                            onclick="permissionAddToServer()"> Create Permission</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // Function to add permission details to the table
        function permissionAddToTable() {
            // Prevent default form submission behavior
            this.event.preventDefault();

            // Retrieve input values
            var permission_name = $("#permission_name").val();
            var permission_group = $("#permission_group").find("option:selected").val();
            var route_name = $("#route_name").val();
            var permission_type = $("#permission_type").find("option:selected").val();

            // Clear previous error messages
            $('#errorMsg').empty();
            $('#errorMsg1').empty();

            // Validate input fields
            if (permission_name.length != 0 && permission_group.length != 0 && route_name.length != 0 && permission_type
                .length != 0) {
                // Append new row to the permission transfer table
                $('#permission_transfer_table_body').append('<tr>\
                <td>' + route_name + '</td>\
                <td ">' + permission_name + '</td>\
                <td>' + permission_group + '</td>\
                <td ">' + permission_type + '</td>\
                <td><button class="btn-remove" style="background: transparent; border: none;" value=""><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
            </tr>');
            } else {
                // Notify user if all fields are required
                $('#add_btn').notify('Required all fields to add.', {
                    className: 'error',
                    position: 'bottom left'
                });
            }
        }

        // Event handler for removing a permission row from the table
        $("#permission_transfer_table").on('click', '.btn-remove', function() {
            $(this).closest('tr').remove();
        });

        // Function to transfer permissions to server
        function permissionAddToServer() {
            // Prevent default form submission behavior
            this.event.preventDefault();

            let permissions = {};
            let permissionList = [];

            // Check if there are rows in the permission transfer table
            if ($('#permission_transfer_table tr').length > 1) {
                $('#errorMsg').empty();
                $('#errorMsg1').empty();

                // Iterate through each row of the permission transfer table
                $('#permission_transfer_table').find('> tbody > tr').each(function() {
                    let permission = {};

                    // Extract data from table cells and populate permission object
                    permission["route_name"] = $(this).find("td:eq(0)").text();
                    permission["permission_name"] = $(this).find("td:eq(1)").text();
                    permission["permission_group"] = $(this).find("td:eq(2)").text();
                    permission["permission_type"] = $(this).find("td:eq(3)").text();

                    // Push permission object to permissionList array
                    permissionList.push(permission);
                });

                // Assign permissionList array to permissions object
                permissions["permissionList"] = permissionList;

                // Call function to transfer permissions to server
                productTransfer(permissions);
            } else {
                // Notify user to add at least one permission to submit
                $('#errorMsg1').text('Please add at least one permission to submit.');
            }
        }

        // Function to send permission data to server via AJAX
        function productTransfer(jsonData) {
            $.ajax({
                type: "POST",
                url: "/permission-create",
                data: JSON.stringify(jsonData),
                dataType: "json",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $.notify({
                        // Adding icon and message
                        icon: 'fa fa-bell',
                        message: response.message,
                    }, {

                        type: 'success',
                        placement: {
                            from: "top",
                            align: 'right',
                        },
                    });
                    // Reset form and table after successful submission
                    resetButton();
                }
            });
        }

        // Function to reset form and table, and clear error messages
        function resetButton() {
            $('#errorMsg').empty();
            $('#errorMsg1').empty();
            $("#permission_transfer_table").find("tr:gt(0)").remove();
            // Additional reset logic if needed
            $('form').on('reset', function() {
                // Additional reset actions
            });
        }
    </script>
@endsection
{{-- var action =
    '<td> <div class="form-button-action"> 
        <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> 
        <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>'; --}}
