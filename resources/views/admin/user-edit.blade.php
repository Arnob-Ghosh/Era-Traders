@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Update User</h3>
            
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Update User</h4>
                   
                </div>
            </div>
            <div class="card-body">

                <form id="EditUserForm" action="" method="POST" enctype="multipart/form-data">
                                  
                    {{ csrf_field() }}
                    <div class="row">
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="name" style="font-weight: normal;">Name<span class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control w-75" id="edit_name" name="name"
                            placeholder="Enter Name" value="">
                            <h6 class="text-danger pt-1" id="edit_wrongname" style="font-size: 14px;"></h6>

                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="email" style="font-weight: normal;">Email<span class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control  w-75" id="edit_email" name="email"
                            placeholder="Enter Email" value="">
                            <h6 class="text-danger pt-1" id="edit_wrongemail" style="font-size: 14px;"></h6>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="password" style="font-weight: normal;">Password<span class="text-danger"><strong>*</strong></span></label>
                                <input type="password" class="form-control  w-75" id="edit_password" name="password"
                            placeholder="Enter Password">
                            <h6 class="text-danger pt-1" id="edit_wrongpassword" style="font-size: 14px;"></h6>

                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="password_confirmation" style="font-weight: normal;">Confirm Password<span class="text-danger"><strong>*</strong></span></label>
                            <input type="password" class="form-control w-75" id="edit_password_confirmation"
                                name="password_confirmation" placeholder="Confirm password">
                            <h6 class="text-danger pt-1" id="edit_wrongpassword_confirmation" style="font-size: 14px;"></h6>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="contactnumber" style="font-weight: normal;">Contact Number<span class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control w-75" id="edit_contactnumber" name="contactnumber"
                            value="">
                            <h6 class="text-danger pt-1" id="edit_wrongcontactnumber" style="font-size: 14px;"></h6>

                        </div>
                     
                    </div>

                 

                    <div class="row">
                <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-2">Update</button>
                        <button type="reset" value="Reset" class="btn btn-outline-danger mt-2 col-form-label" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>


    var user_id = $('#user_id').val();
    fetchUser(user_id);
    
    function resetButton(){
        $('form').on('reset', function() {
            setTimeout(function() {

            });
        });
    }
      
function fetchUser(id) {
    $.ajax({
        type: "GET",
        url: "/user-edit/" + id,
        success: function (response) {
            // Populate form fields with user data if request is successful
            if (response.status == 200) {
                $("#edit_name").val(response.user.name);
                $("#edit_email").val(response.user.email);
                $("#edit_contactnumber").val(response.user.contact_number);
            }
        },
    });
}
// Submit handler for editing user form
$(document).on("submit", "#EditUserForm", function (e) {
    e.preventDefault();
    var user_id = $("#user_id").val();
    // Prepare form data for AJAX submission
    let EditFormData = new FormData($("#EditUserForm")[0]);
    EditFormData.append("_method", "PUT");
    // AJAX request to update user data
    $.ajax({
        type: "POST",
        url: "/user-edit/" + user_id,
        data: EditFormData,
        contentType: false,
        processData: false,
        success: function (response) {
            if ($.isEmptyObject(response.error)) {
                $(location).attr("href", "/users-list");
            } else {
                // Display error messages if there are validation errors
                printErrorEditMsg(response.error);
            }
        },
    });
});
// Function to print error messages for editing user form
function printErrorEditMsg(message) {
    $("#edit_wrongname").empty();
    $("#edit_wrongemail").empty();
    $("#edit_wrongcontactnumber").empty();
    $("#edit_wrongpassword").empty();
    $("#edit_wrongpassword_confirmation").empty();

    if (message.name == null) {
        name = "";
    } else {
        name = message.name[0];
    }

    if (message.email == null) {
        email = "";
    } else {
        email = message.email[0];
    }

    if (message.contactnumber == null) {
        contactnumber = "";
    } else {
        contactnumber = message.contactnumber[0];
    }

    if (message.password == null) {
        password = "";
    } else {
        password = message.password[0];
    }
    // Append error messages to respective fields
    $("#edit_wrongname").append('<span id="">' + name + "</span>");
    $("#edit_wrongemail").append('<span id="">' + email + "</span>");
    $("#edit_wrongcontactnumber").append(
        '<span id="">' + contactnumber + "</span>"
    );
    $("#edit_wrongpassword").append('<span id="">' + password + "</span>");
}

    </script>
@endsection
