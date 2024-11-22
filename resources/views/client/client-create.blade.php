@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Customer Management</h3>
            <button class="btn btn-primary btn-round ms-auto" id="client_create"> <i class="fa fa-plus"></i> Add Customer</button>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Customer Management</h4>

                </div>
            </div>
            <div class="card-body">

                <div class="table-respponsive ">
                    {{-- ClienT Table --}}
                    <table class="display table table-striped table-hover" id="client_table">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    #
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Name </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Contact</th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Email</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Address</th>


                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="CreateClientMODAL" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel"aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>CREATE CLIENT</strong></h5>
                </div>
                {{-- Create Client --}}
                <form id="AddCustomerForm" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-body mb-2">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="customername" class="form-label" style="font-weight: normal;">Customer
                                        Name<span class="text-danger"><strong>*</strong></span></label>
                                    <input type="text" class="form-control w-75" name="customername" id="customername"
                                        placeholder="e.g. Mike Tyson">
                                    <h6 class="text-danger pt-1" id="wrongcustomername" style="font-size: 14px;"></h6>

                                </div>
                            </div>
                            
                        </div>
                        <div class="col-12">
                            <div class="form-group pt-3">
                                <label for="mobile" class="form-label" style="font-weight: normal;">Contact
                                    No.<span class="text-danger"><strong>*</strong></span></label>
                                <input type="text" class="form-control w-75" name="mobile" id="mobile"
                                    placeholder="e.g. 01XXXXXXXXX">
                                <h6 class="text-danger pt-1" id="wrongmobile" style="font-size: 14px;"></h6>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group pt-3">
                                <label for="customeraddress" class="form-label" style="font-weight: normal;">Address
                                    <span
                                        style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
                                <input type="text" class="form-control w-75" name="customeraddress" id="customeraddress"
                                    placeholder="e.g. Palong, Noriya, Dhaka">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group pt-3">
                                <label for="customeremail" class="form-label" style="font-weight: normal;">Email
                                    Address <span
                                        style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
                                <input type="email" class="form-control w-75" name="customeremail" id="customeremail"
                                    placeholder="e.g. mike_tyson@xmail.com">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="note" class="form-label" style="font-weight: normal;">Note <span
                                        style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
                                <textarea class="form-control w-75" name="note" id="note" rows="3"
                                    placeholder="Any notes e.g. customer who wants buy wire"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-2">
                        <button id="close" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Edit Customer Modal -->
    <div class="modal fade" id="EDITClientMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE CUSTOMER</strong></h5>
                </div>


                <!-- Update Customer Form -->
                <form id="UPDATECustomerFORM" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="modal-body">

                        <input type="hidden" name="customerid" id="customerid">

                        <div class="form-group mb-3">
                            <label class="form-label">Customer Name<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" id="edit_customername" name="customername" class="form-control">
                            <h6 class="text-danger pt-1" id="edit_wrongcustomername" style="font-size: 14px;"></h6>

                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Contact No.<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" id="edit_contactnumber" name="mobile" class="form-control">
                            <h6 class="text-danger pt-1" id="edit_wrongcontactnumber" style="font-size: 14px;"></h6>

                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Email Address <span
                                    style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
                            <input type="email" id="edit_customeremail" name="customeremail" class="form-control"
                                placeholder="update email e.g. mike_tyson@xmail.com">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Address <span
                                    style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
                            <input type="text" id="edit_customeraddress" name="customeraddress" class="form-control"
                                placeholder="update address here e.g. Banasree, Dhaka">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Note <span
                                    style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
                            <textarea class="form-control" name="note" id="edit_note" rows="2" placeholder="add notes for customer"></textarea>
                        </div>





                    </div>
                    <div class="modal-footer">
                        <button id="closes" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
                <!-- End Update Customer Form -->

            </div>
        </div>
    </div>
    <!-- End Edit Customer Modal -->
    {{-- Delete Client Modal --}}
    <div class="modal fade" id="DELETECustomerMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form id="DELETECustomerFORM" method="POST" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}


                    <div class="modal-body">
                        <input type="hidden" name="" id="customerid">
                        <h5 class="text-center">Are you sure you want to delete?</h5>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="cancel btn btn-secondary cancel_btn"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="delete btn btn-danger">Yes</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // show create modal on click
        $("#client_create").click(function(e) {
            e.preventDefault();
            $("#CreateClientMODAL").modal("show");
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            // CREATE CLIENT
            $(document).on("submit", "#AddCustomerForm", function(e) {
                e.preventDefault();

                let formData = new FormData($("#AddCustomerForm")[0]);
                $.ajax({
                    type: "POST",
                    url: "/client-create",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if ($.isEmptyObject(response.error)) {
                            $('#AddCustomerForm')[0].reset();
                            window.location.reload();
                        } else {
                            printErrorMsg(response.error);
                        }
                    },
                });
            });
            // show customised error messege
            function printErrorMsg(message) {
                $("#wrongcustomername").empty();
                $("#wrongmobile").empty();

                if (message.customername == null) {
                    customername = "";
                } else {
                    customername = message.customername[0];
                }
                if (message.mobile == null) {
                    mobile = "";
                } else {
                    mobile = message.mobile[0];
                }

                $("#wrongcustomername").append(
                    '<span id="">' + customername + "</span>"
                );
                $("#wrongmobile").append('<span id="">' + mobile + "</span>");
            }
        });

        // EDIT CLIENT
        $(document).on("click", ".edit_btn", function(e) {
            e.preventDefault();

            var clientId = $(this).val();
            // alert(clientId);
            $("#EDITClientMODAL").modal("show");

            $.ajax({
                type: "GET",
                url: "/client-edit/" + clientId,
                success: function(response) {
                    if (response.status == 200) {
                        $("#edit_customername").val(response.client.name);
                        $("#edit_contactnumber").val(response.client.mobile);
                        $("#edit_customeremail").val(response.client.email);
                        $("#edit_customeraddress").val(response.client.address);
                        $("#edit_note").val(response.client.note);
                        $("#edit_storeid").val(response.client.storeId);
                        if (response.client.image == null) {
                            $("#edit_image").attr("src", "../uploads/clients/user.png");
                        } else {
                            $("#edit_image").attr(
                                "src",
                                "../uploads/clients/" + response.client.image
                            );
                        }
                        $("#customerid").val(clientId);
                    }
                },
            });
        });

        // //UPDATE SUPPLIER
        $(document).on("submit", "#UPDATECustomerFORM", function(e) {
            e.preventDefault();

            var id = $("#customerid").val();

            let EditFormData = new FormData($("#UPDATECustomerFORM")[0]);

            EditFormData.append("_method", "PUT");

            $.ajax({
                type: "POST",
                url: "/client-edit/" + id,
                data: EditFormData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if ($.isEmptyObject(response.error)) {
                        $("#EDITCustomerMODAL").modal("hide");
                        $.notify(response.message, "success");
                        $('#UPDATECustomerFORM')[0].reset();

                        window.location.reload();
                    } else {
                        $("#edit_wrongcustomername").empty();
                        $("#edit_wrongcontactnumber").empty();

                        if (response.error.customername == null) {
                            customername = "";
                        } else {
                            customername = response.error.customername[0];
                        }
                        if (response.error.mobile == null) {
                            mobile = "";
                        } else {
                            mobile = response.error.mobile[0];
                        }

                        $("#edit_wrongcustomername").append(
                            '<span id="">' + customername + "</span>"
                        );
                        $("#edit_wrongcontactnumber").append(
                            '<span id="">' + mobile + "</span>"
                        );
                    }
                },
            });
        });

        // //DELETE SUPPLIER
        $(document).ready(function() {
            $("#client_table").on("click", ".delete_btn", function() {
                var customerId = $(this).data("value");

                $("#customerid").val(customerId);

                $("#DELETECustomerFORM").attr("action", "/client-delete/" + customerId);

                $("#DELETECustomerMODAL").modal("show");
            });
        });
        // Show datatable 
        $(document).ready(function() {
            var t = $("#client_table").DataTable({
                ajax: {
                    url: "/client-list-data",
                    dataSrc: "customer",
                },
                columns: [{
                        data: null
                    },

                    {
                        data: "name"
                    },

                    {
                        data: "mobile"
                    },

                    {
                        render: function(data, type, row, meta) {
                            if (row.email == null) {
                                var email = "N/A";
                            } else {
                                var email = row.email;
                            }
                            return email;
                        },
                    },

                    {
                        render: function(data, type, row, meta) {
                            if (row.address == null) {
                                var address = "N/A";
                            } else {
                                var address = row.address;
                            }
                            return address;
                        },
                    },
                    {
                        render: getBtns,
                    },
                ],
                columnDefs: [{
                    searchable: true,
                    orderable: true,
                    targets: 0,
                }, ],
                order: [
                    [1, "asc"]
                ],
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 20, -1],
                    [5, 10, 20, "Todos"],
                ],
            });

            t.on("order.dt search.dt", function() {
                t.on("draw.dt", function() {
                    var PageInfo = $("#client_table").DataTable().page.info();
                    t.column(0, {
                            page: "current"
                        })
                        .nodes()
                        .each(function(cell, i) {
                            cell.innerHTML = i + 1 + PageInfo.start;
                        });
                });
            }).draw();
        });
        // load buttons in datatable
        function getBtns(data, type, row, meta) {
            var id = row.id;
            return (
                '<button type="button" value="' +
                id +
                '" class="edit_btn btn btn-secondary " title="Edit"><i class="fas fa-edit fa-lg"></i></button>\
                	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger " data-value="' +
                id +
                '" title="Delete"><i class="fas fa-trash fa-lg"></i></a>'
            );
        }
        // close modals 
        $("#close").click(function(e) {
            e.preventDefault();

            $("#CreateClientMODAL").modal("hide");
        });
        $("#closes").click(function(e) {
            e.preventDefault();

            $("#EDITClientMODAL").modal("hide");
        });
        $(".cancel_btn").click(function(e) {
            e.preventDefault();

            $("#DELETECustomerMODAL").modal("hide");
        });
    </script>
@endsection
