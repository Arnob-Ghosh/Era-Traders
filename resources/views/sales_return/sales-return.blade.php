@extends('layouts.admin')



@section('content')
    <div class="page-inner">
        <div class="page-header">

        </div>



        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Sales Return</h4>

                          
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">


                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="ref_id" class="form-label selectpicker" style="font-weight: normal;">
                                            Sales Referece No. <span class="text-danger"><strong>*</strong></span>
                                        </label><br>
                                        <select id="ref_id" name="ref_id" class="selectpicker form-control"
                                            data-live-search="true">
                                            <option value="" disabled selected>Select  Referece No. </option>
                                            @foreach ($sales as $sale)
                                                <option value="{{ $sale->ref_id }}" >{{ $sale->ref_id }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-6">

                                    </div>

                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <h6 id= "customer_show"></h6>
                                        <h6 id="contact_show"> </h6>

                                    </div>
                                </div>
                                

                            </div>
                            <div class="row mt-3">
                                <div class="table-responsive">
                                    <table id="saleas_table" class="display table table-striped table-hover">
                                        <thead>
                                            <th>Sl</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Qty</th>
                                            <th>Unit</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                            

                                        </thead>

                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                          
                           

                            <div class="row">
                                <div class="col-6 text-end mt-5 mr-4">
                                    <button class="btn btn-primary sale-return-submit" id="sale">submit </button>

                                </div>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

    <script>
    


    let deletedSalesIds = []; // Array to store deleted sales IDs

$("#ref_id").change(function (e) {
    e.preventDefault();

    var selectedRefId = $(this).val();

    $.ajax({
        type: "GET",
        url: "/sales-info-data",
        data: { ref_id: selectedRefId },
        success: function (response) {
            if (response.status === 200 && response.data.length > 0) {
                var salesData = response.data;

                // Extract customer name and contact number
                var customer_name = salesData[0].customer_name || 'N/A';
                var contact_num = salesData[0].mobile || 'N/A'; // Assuming 'mobile' is contact number

                // Update customer details
                $("#customer_show").text('Customer: ' + customer_name);
                $("#contact_show").text('Contact: ' + contact_num);

                // Clear previous table data
                $("#saleas_table tbody").empty();

                // Append sales data to the table
                $.each(salesData, function (index, sale) {
                    $("#saleas_table tbody").append(`
                        <tr id="row-${sale.id}">
                            <td>${index + 1}</td>
                            <td>${sale.product_name}</td>
                            <td>${sale.category_name}</td>
                            <td><input type="number" class="form-control-sm sale-quantity " 
                                    value="${sale.sale_quantity}" min="0" ></td>
                            <td>${sale.sale_unit}</td>
                            <td>${sale.unit_price}</td>
                            <td><input type="number" class="form-control-sm sale-price " 
                                    value="${sale.sale_price}" min="0"></td>
                            <td>
                                <button class="btn btn-link btn-danger btn-sm  delete-sale" data-id="${sale.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                // No sales data found
                $("#customer_show").text('Customer: N/A');
                $("#contact_show").text('Contact: N/A');
                $("#saleas_table tbody").empty().append('<tr><td colspan="8" class="text-center">No sales data found</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
});

// Handle delete button click
$(document).on("click", ".delete-sale", function () {
    let saleId = $(this).data("id"); // Get sale ID from button
    deletedSalesIds.push(saleId); // Store ID in array
    $("#row-" + saleId).remove(); // Remove row from table

    console.log("Deleted Sales IDs:", deletedSalesIds); // Debugging
});


$(document).ready(function () {
    
});
// Handle Submit Button Click
$(".sale-return-submit").click(function () {
    alert();
    let salesData = [];

    // Loop through table rows and collect data
    $("#saleas_table tbody tr").each(function () {
        let row = $(this);
        let saleId = row.find(".delete-sale").data("id"); // Get sale ID
        let quantity = parseFloat(row.find(".sale-quantity").val()) || 0;
        let price = parseFloat(row.find(".sale-price").val()) || 0;


        salesData.push({
            id: saleId,
            sale_quantity: quantity,
            sale_price: price
        });
    });

    // Prepare the request payload
    let requestData = {
        sales: salesData, // Updated sales data
        deleted_sales: deletedSalesIds // Deleted sale IDs
    };

    console.log("Sending Data:", requestData); // Debugging

    // AJAX POST request
    $.ajax({
        type: "POST",
        url: "/sales-retutrn-store", // Replace with actual backend route
        data: requestData,
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Ensure CSRF token
    },
        success: function (response) {
            if (response.status === 200) {
                alert("Sales return submitted successfully!");
                location.reload(); // Reload page after submission
            } else {
                alert("Error submitting sales return.");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("Something went wrong!");
        }
    });
});


    </script>

@endsection
