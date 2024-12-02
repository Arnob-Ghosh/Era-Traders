@extends('layouts.admin')
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- DataTables Bootstrap 5 Integration CSS -->
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
@endsection
@section('js')
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>



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
                dom: 'lBfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
                <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="invoiceModalLabel">Sales Detail</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="invoice-header row ">
                                    <div class="col-4"><h3>Invoice</h3> </div>
                                    <div class="col-4"> <p>Invoice ID: #${response.ref_id}</p></div>
                                    <div class="col-4"><p>Customer: ${response.customer_name} <br> ${response.customer_mobile} </p></div>
                                </div>
                                   <p>Date: ${response.date}</p> 

                                <div class="invoice-details">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${response.items
                                                .map(
                                                    (item) => `
                                                    <tr>
                                                        <td>${item.product_name}</td>
                                                        <td>${item.category_name}</td>
                                                        <td>${item.sale_unit}</td>
                                                        <td>${item.sale_quantity}</td>
                                                        <td>${item.unit_price}</td>
                                                        <td>${item.sale_price}</td>
                                                    </tr>`
                                                )
                                                .join('')}
                                        </tbody>
                                    </table>
                                </div>
                                <div class="invoice-summary text-end me-3">
                                    <h4 style="color:black;">Total Amount: ${response.total_sale_price}</h4>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>`;

                    // Append the modal to the body and show it
                    $('body').append(invoiceHtml);
                    $('#invoiceModal').modal('show');

                    // Remove the modal after it's hidden
                    $('#invoiceModal').on('hidden.bs.modal', function() {
                        $(this).remove();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching invoice data:', error);
                    alert('Failed to fetch invoice details. Please try again.');
                }
            });
        });
       
  

    </script>
@endsection
