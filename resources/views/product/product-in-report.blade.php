@extends('layouts.admin')
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- DataTables Bootstrap 5 Integration CSS -->
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Product Management</h3>
            <ul class="breadcrumbs mb-3">

            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Product In</h4>
                            <a href="/product-in" class="btn btn-primary btn-round ms-auto" ><i class="fa fa-plus"></i>Product In</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="row">
                            <div class="col-12">
                                <div id="form_div">

                                    <form id="productInReportForm" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label for="startdate" style="font-weight: normal;">From</label>
                                                <input type="date" class="form-control" id="startdate" name="startdate">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="enddate" style="font-weight: normal;">To</label>
                                                <input type="date" class="form-control" id="enddate" name="enddate">
                                            </div>

                                            <div style="padding-top: 32px;" class="form-group col-md-4">
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
                            <table id="product_in_table" class="display table table-striped table-hover">
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
            var t = $('#product_in_table').DataTable({
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "url": "/product-in-report-data",
                    "dataSrc": "data",
                    "dataType": "json",
                    data: function(d) {
                        d.startdate = $('#startdate').val(); // Send start date
                        d.enddate = $('#enddate').val(); // Send end date
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
                        data: 'product_name',
                        title: 'Product Name'
                    },
                    {
                        data: 'category_name',
                        title: 'Category'
                    },
                    {
                        data: 'unit',
                        title: 'Unit'
                    },
                    {
                        data: 'quantity',
                        title: 'Quantity'
                    },
                    {
                        data: 'sub_unit',
                        title: 'Sub Unit Type'
                    },
                    {
                        data: 'sub_unit_quantity',
                        title: 'Sub Unit'
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
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 20, -1],
                    [5, 10, 20, 'Todos']
                ],
                dom: 'lBfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            });

            // Re-draw the table on form submission
            $('#productInReportForm').on('submit', function(e) {
                e.preventDefault();
                t.ajax.reload();
            });

            t.on('order.dt search.dt', function() {
                t.on('draw.dt', function() {
                    var PageInfo = $('#product_in_table').DataTable().page.info();
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
    </script>
@endsection
