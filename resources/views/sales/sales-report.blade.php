@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Sales Report</h3>
            <ul class="breadcrumbs mb-3">

            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Sales Report</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="row">
                            <div class="col-12">
                                <div id="form_div">

                                    <form id="SaleReportForm" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label for="customer" style="font-weight: normal;">Client</label>
                                                <select name="client" id="client" class="form-select w-100 p-2 mt-1"
                                                    title="Select role" data-width="60%">
                                                    <option value="all">All</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="startdate" style="font-weight: normal;">From</label>
                                                <input type="date" class="form-control" id="startdate" name="startdate">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="enddate" style="font-weight: normal;">To</label>
                                                <input type="date" class="form-control" id="enddate" name="enddate">
                                            </div>

                                            <div style="padding-top: 32px;" class="form-group col-md-3">
                                                <button type="submit" class="btn btn-primary"
                                                    id="gen_btn">Generate</button>
                                                <button id="reset_btn" type="button" class="btn btn-outline-danger"
                                                    onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table id="sales_report_table" class="display table table-striped table-hover">
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
@endsection
@section('js')
<script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>

    <script>
        $(document).ready(function() {
            // Set this month's start and end dates
            const today = new Date();
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            // Format dates as YYYY-MM-DD
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            // Set initial values for the date fields
            $('#startdate').val(formatDate(firstDayOfMonth));
            $('#enddate').val(formatDate(lastDayOfMonth));

            // DataTable Initialization
            var t = $('#sales_report_table').DataTable({
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "url": "/sales-report-data",
                    "dataSrc": "data",
                    "dataType": "json",
                    data: function(d) {
                        d.startdate = $('#startdate').val(); // Send start date
                        d.enddate = $('#enddate').val(); // Send end date
                        d.client = $('#client').find(':selected').val(); // Get the selected value

                    },
                },
                columns: [{
                        data: null
                    }, // Auto-index
                    {
                        data: 'date',
                        title: 'Date'
                    },
                    {
                        data: 'ref_id',
                        title: 'Reference'
                    },
                    {
                        data: 'customer_name',
                        title: 'Customer'
                    },
                    {
                        data: 'total_sale_price',
                        title: 'Amount'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.invoice_price && row.invoice_price[0] ? row.invoice_price[0]
                                .paid : 0;
                        },
                        title: 'Paid '
                    },
                    {
                        data: null, // Actions column
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-md btn-transparent" id="view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                            `;
                        }
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
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 20, -1],
                    [5, 10, 20, 'all']
                ],
                buttons: ['excel', 'print'],
                dom: '<"row"<"col-sm-1"l><"col-sm-2"B>>' + 'frtip',
            });

            // Re-draw the table on form submission
            $('#SaleReportForm').on('submit', function(e) {
                e.preventDefault();
                t.ajax.reload();
            });

            t.on('order.dt search.dt', function() {
                t.on('draw.dt', function() {
                    var PageInfo = $('#sales_report_table').DataTable().page.info();
                    t.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + PageInfo.start;
                    });
                });
            }).draw();
        });



        function resetButton() {
            $('#form_div').find('form')[0].reset();
            $('form').on('reset', function() {
                const today = new Date();
                const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                $('#startdate').val(formatDate(firstDayOfMonth));
                $('#enddate').val(formatDate(lastDayOfMonth));
            });
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        $(document).on('click', '#view', function() {
            // Retrieve the selected row's data
            const row = $(this).closest('tr');
            const rowData = $('#sales_report_table').DataTable().row(row).data();
            const refId = rowData.ref_id; // Extract `ref_id`

            // Make an AJAX request to fetch data
            $.ajax({
                url: '/sales-report-invoice',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ref_id: refId
                },
                success: function(response) {
                    // Construct the invoice HTML
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
                                        <td>${response.date}</td>
                                    </tr>
                                    <tr>
                                        <td>Invoice No:</td>
                                        <td>#${response.ref_id}</td>
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
                                    ${response.items .map((item,index) => `
                                                                    <tr>
                                                                        <td>${index + 1}</td>
                                                                        <td>${item.product_name}</td>
                                                                        <td>${item.category_name}</td>
                                                                        <td>${item.sale_quantity}</td>
                                                                        <td>${item.unit}</td>
                                                                        <td>${(item.sale_price/item.sale_quantity).toFixed(2)}</td>
                                                                        <td>${item.sale_price}</td>
                                                                    </tr>`
                                                            )
                                                            .join('')}
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
                                <td style="border: 1px solid black; ">${response.total_sale_price}</td>
                            </tr>
                            <tr style="border-top:10px;">
                                <td style="border: 1px solid black; ">Discount:</td>
                                <td style="border: 1px solid black; ">0</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; ">Total:</td>
                                <td style="border: 1px solid black; ">${response.total_sale_price}</td>
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
                    </html>

                                  `;

                    // Append the modal to the body and show it
                    // $('body').append(invoiceHtml);
                    // $('#invoiceModal').modal('show');

                    // // Remove the modal after it's hidden
                    // $('#invoiceModal').on('hidden.bs.modal', function() {
                    //     $(this).remove();
                    // });
                    // Load the generated invoice into the modal
                    $('#invoiceModalBody').html(invoiceHtml);
                    // $('#invoiceModal').modal('show');

                        $('#invoiceModalBody').print();
                        // let printInterval = setInterval(function() {
                        //     if (document.hasFocus()) {
                        //         clearInterval(printInterval);
                        //         $('#invoiceModal').modal('hide'); // Close the modal
                        //         window.location = "/sales";
                        //     }
                        // }, 500);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching invoice data:', error);
                    alert('Failed to fetch invoice details. Please try again.');
                }
            });
        });
    </script>
@endsection
