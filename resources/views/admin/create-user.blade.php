@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Create User</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Create User</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

            </ul>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Create User</h4>
                   
                </div>
            </div>
            <div class="card-body">

                <form id="AddUserForm" action="" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="name" style="font-weight: normal;">Name<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control w-75" id="name" name="name"
                                placeholder="e.g. Jhon">
                            <h6 class="text-danger pt-1" id="wrongname" style="font-size: 14px;"></h6>

                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="email" style="font-weight: normal;">Email<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="email" class="form-control w-75" id="email" name="email"
                                placeholder="jhon@example.com">
                            <h6 class="text-danger pt-1" id="wrongemail" style="font-size: 14px;"></h6>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="password" style="font-weight: normal;">Password<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="password" class="form-control w-75" id="password" name="password"
                                placeholder="User password here ">
                            <h6 class="text-danger pt-1" id="wrongpassword" style="font-size: 14px;"></h6>

                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="password_confirmation" style="font-weight: normal;">Confirm
                                Password<span class="text-danger"><strong>*</strong></span></label>
                            <input type="password" class="form-control w-75" id="password_confirmation"
                                name="password_confirmation" placeholder="Confirm password">
                            <h6 class="text-danger pt-1" id="wrongpassword_confirmation" style="font-size: 14px;"></h6>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="contactnumber" style="font-weight: normal;">Contact Number<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control w-75" id="contactnumber" name="contactnumber"
                                placeholder="e.g. 01XXXXXXXXX">
                            <h6 class="text-danger pt-1" id="wrongcontactnumber" style="font-size: 14px;">
                            </h6>

                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="roles" style="font-weight: normal;">Assign Roles<span
                                    class="text-danger"><strong>*</strong></span></label><br>
                            <select name="roles" id="roles" class="form-select w-75" title="Select role" data-width="60%">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <h6 class="text-danger pt-1" id="wrongroles" style="font-size: 14px;"></h6>

                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="image" style="font-weight: normal;">Profile Image</label>
                            <input type="file" class="form-control w-75" id="image" name="image" accept="image/*">
                            <h6 class="text-danger pt-1" id="wrongimage" style="font-size: 14px;"></h6>
                        </div>

                    </div>



                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary mt-2">Create</button>
                            <button type="reset" value="Reset" class="btn btn-outline-danger mt-2 col-form-label"
                                onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // Submit handler for adding user form
            $(document).on('submit', '#AddUserForm', function(e) {
                e.preventDefault();

                // Gather form data into FormData object
                let formData = new FormData($('#AddUserForm')[0]);

                // AJAX request to create user
                $.ajax({
                    type: "POST",
                    url: "/create-user",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // If no errors, redirect to user list page
                        if ($.isEmptyObject(response.error)) {
                            $('#AddUserForm')[0].reset();
                            $(location).attr('href', '/users-list');
                        } else {
                            // If there are errors, call printErrorMsg function to display them
                            printErrorMsg(response.error);
                        }
                    }
                });
            });

            // Function to print error messages for user form
            function printErrorMsg(message) {
                // Clear existing error messages
                $('#wrongname').empty();
                $('#wrongemail').empty();
                $('#wrongpassword').empty();
                $('#wrongpassword_confirmation').empty();
                $('#wrongcontactnumber').empty();

                if (message.name == null) {
                    name = ""
                } else {
                    name = message.name[0]
                }

                if (message.email == null) {
                    email = ""
                } else {
                    email = message.email[0]
                }

                if (message.contactnumber == null) {
                    contactnumber = ""
                } else {
                    contactnumber = message.contactnumber[0]
                }


                if (message.password == null) {
                    password = ""
                } else {
                    password = message.password[0]
                }

                $('#wrongname').append('<span id="">' + name + '</span>');
                $('#wrongemail').append('<span id="">' + email + '</span>');
                $('#wrongpassword').append('<span id="">' + password + '</span>');
                $('#wrongcontactnumber').append('<span id="">' + contactnumber + '</span>');
            }
        });
    </script>
@endsection
