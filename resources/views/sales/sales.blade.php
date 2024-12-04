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
                            <h4 class="card-title">Sales</h4>
                            
                            <button style="font-size: medium;padding: 7px;margin-bottom: 6; margin-left: auto;"
                            id="addcustomer" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal"><i
                                class="fas fa-user-plus"></i> Add Customer</button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">


                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="customer" class="form-label selectpicker" style="font-weight: normal;">
                                            Customer<span class="text-danger"><strong>*</strong></span>
                                        </label><br>
                                        <select id="customer" name="customer" class="form-select">
                                            <option value="" disabled selected>Select Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-6">
                                        
                                    </div>

                                </div>


                                <div class="table-responsive">
                                    <table id="saleas_table" class="display table table-striped table-hover">
                                        <thead>
                                            <th>Product</th>
                                            <th>Catgory</th>
                                            <th>Unit</th>
                                            <th>Price</th>
                                            <th>Action</th>

                                        </thead>

                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="note" class="form-label " style="font-weight: normal;">
                                            Note<span class="text-danger"><strong></strong></span>
                                        </label><br>
                                        <textarea name="note" id="note" class="form-control"cols="30" rows="1"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-end mt-5 mr-4">
                                        <button class="btn btn-primary"id="sale">submit </button>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-12">

                                <div class="table-responsive">
                                    <table id="inventory_table" class="display table table-striped table-hover">
                                        <thead>


                                        </thead>

                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Invoice Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="invoiceModalBody">
                    <!-- Invoice HTML will be injected here -->
                </div>
            </div>
        </div>
    </div>

  <!-- Add Customer Modal -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCustomerForm">
                    <div class="form-group">
                        <label for="customerName">Name <span><strong style="color: red;">*</strong></span></label>
                        <input type="text" class="form-control" id="customerName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="customerMobile">Mobile <span><strong style="color: red;">*</strong></span></label>
                        <input type="tel" class="form-control" id="customerMobile" name="mobile" required>
                    </div>
                    <div class="form-group">
                        <label for="customerEmail">Email</label>
                        <input type="email" class="form-control" id="customerEmail" name="email">
                    </div>
                    <div class="form-group">
                        <label for="customerAddress">Address</label>
                        <textarea class="form-control" id="customerAddress" name="address" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="customerNote">Note</label>
                        <textarea class="form-control" id="customerNote" name="note" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCustomer">Save</button>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('js')
    <script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>

    <script>
        // Handle customer form submission
        $('#saveCustomer').click(function(e) {
            e.preventDefault();
            var formData = {
                customername: $('#customerName').val(),
                mobile: $('#customerMobile').val(), 
                email: $('#customerEmail').val(),
                address: $('#customerAddress').val(),
                note: $('#customerNote').val()
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/client-create',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if(response.status == 200){
                    // Show success notification
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Success',
                        message: 'Customer added successfully!',
                    }, {
                        type: 'success',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        time: 2000,
                    });
                     // Reload only the customer dropdown
        $.ajax({
            url: '/client-list-data', // You'll need to create this endpoint
            method: 'GET',
            success: function(customers) {
                let options = '';
                options += `<option value="" disabled selected>Select Customer</option>`;
                customers.customer.forEach(function(customer) {
                    options += `<option value="${customer.id}">${customer.name}</option>`;
                });
                $('#customer').html(options);
                    }
                });

                    $('#addCustomerForm')[0].reset();
                    $('#addCustomerModal').modal('hide');

                }else{
                        // Don't close modal on validation error
                        $.notify({
                            icon: 'icon-bell',
                            title: 'Error',
                            message: response.error.customername[0] + ' ' + response.error.mobile[0],
                        }, {
                            type: 'danger',
                    });
                    }

                    // Close modal

                    // Clear form
                },
                error: function(xhr) {
                    // Show error notification
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Error',
                        message: 'Something Went wrong',
                    }, {
                        type: 'danger',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        time: 2000,
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var t = $('#inventory_table').DataTable({
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/inventory-report-data",
                    dataSrc: 'data',
                    "dataType": "json",

                },
                columns: [{
                        data: null,
                        title: '#'
                    },
                    {
                        data: 'id',
                        title: 'id',
                        class: 'd-none'

                    },
                    {
                        data: 'product_name',
                        title: 'Product'
                    },
                    {
                        data: 'category_name',
                        title: 'Category'
                    },
                    {
                        data: null,
                        title: 'Quantity',
                        render: function(data, type, row) {
                            return row.unit_quantity + ' - ' + row.unit_name;
                        }
                    },
                    {
                        data: 'sub_unit_quantity',
                        title: 'SubUnit Quantity',

                    },
                    {
                        data: 'sub_unit',
                        title: 'SubUnit/Unit',

                    },
                    {
                        data: 'unit_price',
                        title: 'Unit Price'
                    },
                    {
                        data: 'sub_unit_price',
                        title: 'Sub Unit Price'
                    },
                ],
                columnDefs: [{
                    searchable: true,
                    orderable: true,
                    targets: 0,
                }],
                order: [
                    [1, 'asc']
                ],
                pageLength: 100000,


            });

            t.on('order.dt search.dt', function() {
                t.on('draw.dt', function() {
                    var PageInfo = $('#inventory_table').DataTable().page.info();
                    t.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + PageInfo.start;
                    });
                });
            }).draw();

            $('#inventory_table tbody').on('click', 'tr', function() {
                var data = $('#inventory_table').DataTable().row(this).data();
                if (data) {
                    var newRow = `
                        <tr>
                            <td>${data.product_name}</td>
                            <td>${data.category_name}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <input type="number" name="quantities[]" class="form-control me-2" style="width: 80px;" />
                                    -
                                    <select name="units[]" class="form-select ms-2" style="width: auto;">
                                        <option value="${data.unit_id}">${data.unit_name}</option>
                                        <option value="${data.sub_unit_id}">${data.sub_unit_name}</option>
                                    </select>
                                </div>
                            </td>
                            <td><input type="number" name="prices[]" class="form-control" value="" /></td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-row">Remove</button>
                            </td>
                            <td class="d-none">
                                <input type="hidden" name="product_id" value="${data.id}">
                            </td>
                        </tr>
                    `;

                    $('#saleas_table tbody').append(newRow);
                    // Update total whenever a row is added
                    updateTotal();

                    // Add event listeners for price changes
                    $('input[name="prices[]"]').on('input', function() {
                        updateTotal();
                    });

                    // Add footer row if it doesn't exist
                    if ($('#saleas_table tfoot').length === 0) {
                        $('#saleas_table').append(`
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td id="total-amount">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        `);
                    }

                    function updateTotal() {
                        let total = 0;
                        $('input[name="prices[]"]').each(function() {
                            let price = parseFloat($(this).val()) || 0;
                            total += price;
                        });
                        $('#total-amount').text(total.toFixed(2));
                    }
                }
            });

            // Remove row from Sales table on button click
            $('#saleas_table').on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });
            $('#invoiceModal').on('hidden.bs.modal', function() {
            window.location.reload(); // Refresh the page
        });
        });
        $('#sale').on('click', function() {
            let salesData = [];
            let isValid = true;

            $('#saleas_table tbody tr').each(function() {
                let row = $(this);
                let productId = row.find('input[name="product_id"]').val();
                let quantity = row.find('input[name="quantities[]"]').val();
                let unitId = row.find('select[name="units[]"]').val();
                let price = row.find('input[name="prices[]"]').val();

                if (!productId || !quantity || !unitId || !price) {
                    isValid = false;
                    return false; // Exit the loop early
                }

                salesData.push({
                    product_id: productId,
                    quantity: quantity,
                    unit_id: unitId,
                    unit_name: row.find('select[name="units[]"] option:selected')
                        .text(), // Optional
                    price: price,
                });
            });
            console.log(salesData)

            if (!isValid) {
                $.notify({
                    icon: 'icon-bell',
                    title: 'Validation Error',
                    message: 'Please Fill neccesery fields.',
                }, {
                    type: 'danger',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    time: 2000,
                });
                return; // Stop the function execution
            }

            // Send AJAX request
            $.ajax({
                url: '/sales-store',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    customer_id: $('#customer').val(),
                    sales: salesData,
                }),
                success: function(response) {
                    // Show success notification
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Success',
                        message: 'Sales data submitted successfully!',
                    }, {
                        type: 'success',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        time: 2000,
                    });

                    // Clear the sales table
                    $('#saleas_table tbody').empty();

                    // Reload the inventory table
                    $('#inventory_table').DataTable().ajax.reload();

                    // Generate the invoice
                    let invoiceHtml = `
                                <!DOCTYPE html>
                                <html lang="en">
                                <head>
                                    <meta charset="UTF-8">
                                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <title>Invoice</title>
                                    <!-- Include Bootstrap CSS or any other styles you want -->
                               <style>
                                    @page {
                                        margin: 0;  /* Remove default page margin for full coverage */
                                    }
                                    body {
                                        margin: 0;  /* Remove default margin */
                                        padding: 0;
                                        font-size: 10px !important;
                                        line-height: 1.2;
                                    }
                                    .card {
                                        margin: 0;
                                        padding: 0;
                                        border: none;
                                        min-height: 100vh;  /* Ensure the card covers the full height */
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: space-between;
                                    }
                                    .card-body {
                                        flex-grow: 1;  /* Make the body expand to fill available space */
                                        padding: 10px;  /* Add a little padding if needed */
                                    }
                                    .invoice-header, .invoice-desc, .invoice-detail, .invoice-item {
                                        font-size: 10px !important;
                                    }
                                    #invoice-custom table, th, td {
                                        font-size: 10px !important;  /* Smaller table fonts in the invoice */
                                    }
                                    h3, h5 {
                                        font-size: 11px !important;
                                    }
                                    .table-responsive {
                                        overflow-x: auto;
                                    }
                                </style>


                                </head>
                                <body style="min-hight:100vh;">
                                    <div class="card " style="min-hight:100vh;">
                                        <div class="card-header row" style="width:100%;">
                                            <div class="invoice-header" style="width:50%;">
                                                <h3 class="invoice-title">Invoice</h3>
                                            
                                            </div>
                                            <div class="invoice-desc" style="width:50%; float:right; text-align:right;">
                                                <div class="invoice-logo">
                                                    <img src="assets/img/examples/logoinvoice.svg" alt="company logo" width="100">
                                                </div>
                                                Palang, Uttar Bazar <br>
                                                Sadar Shariatpur<br>
                                                Byapari Complex
                                            </div>
                                        </div>
                                        <div class="card-body" id="invoice-custom">
                                            
                                            <div class="row" style="width:100%;font-size: 12px;">
                                                <div class="col-md-4 col-lg-4 "style="width:33%;">
                                                    <h5 class="sub">Date</h5>
                                                    <p>${new Date().toLocaleDateString()}</p>
                                                </div>
                                                <div class="col-md-4 col-lg-4 "style="width:33%">
                                                    <h5 class="sub">Invoice ID</h5>
                                                    <p>#${response.invoice_id}</p>
                                                </div>
                                                <div class="col-md-4 col-lg-4 "style="width:33%">
                                                    <h5 class="sub">Invoice To</h5>
                                                    <p>
                                                        ${response.customer_name}<br>
                                                        ${response.customer_mobile}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="invoice-detail">
                                                        <h3 class="title"><strong>Order Summary</strong></h3>
                                                        <div class="invoice-item">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Item</th>
                                                                            <th>Category</th>
                                                                            <th class="text-center">Price</th>
                                                                            <th class="text-center">Quantity</th>
                                                                            <th class="text-end">Totals</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        ${response.sales_items.map(item => `
                                                                                <tr>
                                                                                    <td>${item.product_name}</td>
                                                                                    <td>${item.category}</td>
                                                                                    <td class="text-center">${item.price}</td>
                                                                                    <td class="text-center">${item.quantity} - ${item.sale_unit}</td>
                                                                                    <td class="text-end">${item.total}</td>
                                                                                </tr>
                                                                            `).join('')}
                                                                    
                                                                        <tr>
                                                                            <td colspan="3"></td>
                                                                            <td class="text-center"><strong>Total</strong></td>
                                                                            <td class="text-end">${response.total}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>    
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-md-12"style="text-align:right;
                                position: bottom;">
                                                    <h5 class="sub">Signature</h5>
                                                <hr>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </body>
                                </html>`;


                    // Load the generated invoice into the modal
                    $('#invoiceModalBody').html(invoiceHtml);
                    $('#invoiceModalBody').print();
                    let printInterval = setInterval(function() {
                        if (document.hasFocus()) {
                            clearInterval(printInterval);
                            $('#invoiceModal').modal('hide'); // Close the modal
                        }
                    }, 500);
                    // Ensure you have a container to hold the invoice HTML
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Error',
                        message: 'Failed to submit sales data. Please try again.',
                    }, {
                        type: 'danger',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        time: 2000,
                    });
                },
            });

        });

      
    </script>
@endsection
