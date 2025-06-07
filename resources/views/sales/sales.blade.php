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
                                id="addcustomer" type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addCustomerModal"><i class="fas fa-user-plus"></i> Add Customer</button>
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
                                        <select id="customer" name="customer" class="selectpicker form-control"
                                            data-live-search="true">
                                            <option value="" disabled selected>Select Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" data-mobile="{{ $customer->mobile }}">
                                                    {{ $customer->name }}</option>
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


                                <div class="table-responsive">
                                    <table id="saleas_table" class="display table table-striped table-hover">
                                        <thead>
                                            <th>Product</th>
                                            <th>Category</th>
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
                                        <label for="paid" class="form-label " style="font-weight: normal;">Paid<span
                                                class="text-danger"><strong></strong></span>
                                        </label><br>
                                        <input type="number" name="paid" id="paid" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label for="note" class="form-label " style="font-weight: normal;">
                                            Note<span class="text-danger"><strong></strong></span>
                                        </label><br>
                                        <textarea name="note" id="note" class="form-control"cols="30" rows="1"></textarea>
                                    </div>
                                </div>
                                <div class="row">

                                </div>

                                <div class="row">
                                    <div class="col-12 text-end mt-5 mr-4">
                                        <button class="btn btn-primary"id="sale">submit </button>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <h4 class= "ml-5">Inventory</h4>

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
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
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
                    if (response.status == 200) {
                        // Show success notification
                        $.notify({
                            icon: 'icon-bell',
                            title: 'Customer added successfully!',
                            message: '',
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
                            url: '/client-list-data', // Ensure this endpoint is correctly implemented in your server
                            method: 'GET',
                            success: function(customers) {
                                let options =
                                    '<option value="" disabled selected>Select a customer</option>'; // Dummy option
                                customers.customer.forEach(function(customer) {
                                    options += `
                                <option value="${customer.id}" data-mobile="${customer.mobile}">
                                    ${customer.name}
                                </option>`;
                                });
                                $('#customer').html(options); // Update the select element
                                $('#customer').selectpicker(
                                    'refresh'); // Refresh the selectpicker to apply changes
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching customer data:', error);
                            }
                        });


                        $('#addCustomerForm')[0].reset();
                        $('#addCustomerModal').modal('hide');

                    } else {
                        // Don't close modal on validation error
                        $.notify({
                            icon: 'icon-bell',
                            title: 'Error',
                            message: response.error.customername[0] + ' ' + response.error
                                .mobile[0],
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
            $("#customer").val('');
            $("#customer_show").empty();
            $("#contact_show").empty();
            $('#customer').selectpicker('refresh');

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
                dom: "rftr"


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
                                    <input type="number" name="quantities[]" class="form-control me-2" style="width: 80px;" min="1" />
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
        $(document).ready(function() {

            $(document).on('click', '#sale', function() {

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

                if (!isValid) {
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Please Fill neccesery fields',
                        message: '',
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
                customer = $('#customer').val();
                if (!customer) {
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Please choose customer.',
                        message: '',
                    }, {
                        type: 'danger',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        time: 2000,
                    });
                    return; //
                }
                if (salesData.length === 0) {
                    $.notify({
                        icon: 'icon-bell',
                        title: 'Please add at least one item to the sales table.',
                        message: '',
                    }, {
                        type: 'danger',
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        time: 2000,
                    });
                    return;
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
                        paid: $('#paid').val(),
                        sales: salesData,
                    }),
                    success: function(response) {
                        // Show success notification
                        $.notify({
                            icon: 'icon-bell',
                            title: 'Sales data submitted successfully!',
                            message: '',
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
                        $('#total-amount').text(0);
                        $("#customer").val('');
                        $("#customer_show").empty();
                        $("#contact_show").empty();
                        $("#paid").val('');
                        $('#customer').selectpicker('refresh');

                        // Reload the inventory table
                        $('#inventory_table').DataTable().ajax.reload();

                        // Generate the invoice
                        let invoiceHtml = `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Tax Invoice</title>
            <style>
                    @page {
                        margin: 0; /* Removes all page margins */
                        size: A4; /* Sets the page size to A4 */
                    }
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    line-height: 1.5;
                    color: #000;
                }
                .invoice-container {
                    padding: 20px;
                    margin: auto;
                }
                .header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 20px;
                }
                .header img {
                    max-width: 120px;
                    margin-right: 20px;
                }
                .header-title {
                    flex: 1;
                    text-align: center;
                    margin-right: 30px;
                }
                .header-title h1 {
                    font-size: 20px;
                    margin: 0;
                }
              
                .info-section {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
               
                .sales-table, .pricing-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .sales-table th, .sales-table td,
                .pricing-table th, .pricing-table td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: left;
                }
                .sales-table th {
                    background-color: #f2f2f2;
                }
                .instructions {
                    font-size: 12px;
                    margin-top: 20px;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 12px;
                }
                .signature {
                    text-align: right;
                    margin-top: 40px;
                    font-size: 14px;
                }
                  

            </style>
        </head>
        <body>
            <div class="invoice-container">
                <!-- Header -->
                <div class="header">
                        <h1 class="header-title me-3">Era Traders</h1>

                    
                </div>

                <!-- Info Section -->
                <div class="info-section table table-bordered">
                    <!-- Customer Info -->
                    <table class=" table-bordered">
                        <tbody>
                        <tr>
                            <th colspan="2">BILL TO:</th></tr>

                        <tr>
                            <td>Customer:</td>
                            <td>${response.customer_name}</td>
                        </tr>
                        <tr>
                            <td>Contact No:</td>
                            <td>${response.customer_mobile}</td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>${response.customer_address ? response.customer_address : ""}</td>
                        </tr>
                        </tbody>

                    </table>
                    <br>
                    <br>
                    <table class="table-bordered">
                         <tr>
                            <th colspan="2">Tax Invoice:</th></tr>
                        <tr>
                            <td>Date:</td>
                            <td>${new Date().toLocaleDateString()}</td>
                        </tr>
                        <tr>
                            <td>Invoice No:</td>
                            <td>#${response.invoice_id}</td>
                        </tr>
                        <tr>
                            <td>Customer ID:</td>
                            <td>C-${response.customer_id}</td>
                        </tr>
                    </table>
                </div>
                    <br>
                    <br>
                    <br>


                <!-- Sales Table -->
                <table class="sales-table ">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${response.sales_items.map((item, index) => `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.product_name}</td>
                                        <td>${item.category}</td>
                                        <td>${item.quantity}</td>
                                        <td>${item.sale_unit}</td>
                                        <td>${(item.price / item.quantity).toFixed(2)}</td>
                                        <td>${item.total}</td>
                                    </tr>`).join('')}
                    </tbody>
                </table> <br><br>

             <!-- Combined Pricing and Instructions Table -->
               <table class="table">
    <tr>
        <!-- Instruction Column -->
        <td style="width: 70%; border: 1px solid black; vertical-align: top; ">
            * All prices are quoted in BDT, excluding VAT & Tax.<br>
            * Any damage claims must be made within 7 days of delivery.<br>
            * Goods remain the property of Era Traders until full payment has been received.<br>
        </td>
        <!-- Pricing Column -->
        <td style="width: 30%; ">
            <table class="table table-striped">
                <tr style="border-top:10px;">
                    <td style="border: 1px solid black; ">Sub Total:</td>
                    <td style="border: 1px solid black; ">${response.subtotal}</td>
                </tr>
                <tr style="border-top:10px;">
                    <td style="border: 1px solid black; ">Discount:</td>
                    <td style="border: 1px solid black; ">0</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; ">Total:</td>
                    <td style="border: 1px solid black; ">${response.total}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; ">Paid:</td>
                    <td style="border: 1px solid black; ">${response.paid}</td>
                </tr>
               
            </table>
        </td>
    </tr>
                    <!-- Note/Remarks Section -->
                    <tr>
                        <td colspan="2" style="border: 1px solid black; padding: 8px;">Note/Remarks:</td>
                    </tr><br><br>
                </table>
                <p class="text-center">Palang, Uttar Bazar, Sadar Shariatpur, Byapari Complex</p>
              
                      <div class="footer" style="width: 95%;">
    <p style="text-align: center;">Thank you for your business</p>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px; width: 100%;">
        <div style="text-align: left; width: 50%;">
            <p><u>Received By</u></p>
        </div>
        <div style="text-align: right; width: 50%;">
            <p><u>Authorized By</u></p>
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
                                window.location = "/sales";
                            }
                        }, 500);
                        // Ensure you have a container to hold the invoice HTML
                    },
                    error: function(xhr, status, error) {
                        $.notify({
                            icon: 'icon-bell',
                            title: xhr.responseJSON.error,
                            message: xhr.responseJSON.message,
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

        });
        $("#customer").change(function(e) {
            e.preventDefault();
            var customer_show = $(this).find(':selected').text();
            var contact_show = $(this).find(':selected').data('mobile');
            $("#customer_show").empty().append('Customer :' + customer_show);
            $("#contact_show").empty().append('Contact :' + contact_show);


        });
    </script>
@endsection
