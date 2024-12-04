@extends('layouts.admin')

<!-- Bootstrap Select CSS -->

    
@section('content')
    <div class="page-inner">
        <div class="page-header">
         
    </div>
    <div class="row">
        <div class="col-sm-6 col-lg-4">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <span class="stamp stamp-md bg-secondary justify-content-center d-flex align-items-center me-3" 
                          style="height: 50px; width: 50px; ">
                        <i class="fa fa-dollar-sign" style="font-size: 20px; color: white;"></i>
                    </span>
                    <div ><h4 id="stock_price"> Total Stock Price: </h4>
                        <!-- Empty div that needs to be populated with inventory data -->
                        <!-- Based on the controller, this should display the total inventory price ($inventory_price) -->
                        <!-- Need to add JavaScript to populate this div using the AJAX response data -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Inventory Management</h4>

                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->



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
@endsection

@section('js')
   
    <script>
        $(document).ready(function() {
            var t = $('#inventory_table').DataTable({
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/inventory-report-data",
                    dataSrc: function(json) {
                        // Update the stock value with the price from the response
                        console.log(json);
                        if (json.price) {
                            $('#stock_price').append( json.price.toFixed(2)+' ৳');
                        }
                        return json.data;
                    },
                    dataType: "json",
                },
                columns: [
                    {
                        data: null,
                        title: '#'
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
                        data: null,
                        title: 'SubUnit Quantity',
                        render: function(data, type, row) {
                            return (row.sub_unit_quantity - (row.unit_quantity * row.sub_unit)) +
                                ' - ' + row.sub_unit_name;
                        }
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
                columnDefs: [
                    {
                        searchable: true,
                        orderable: true,
                        targets: 0,
                    }
                ],
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 20, -1],
                    [5, 10, 20, 'All']
                ],
                buttons: ['excel', 'print'],
                dom: '<"row"<"col-sm-1"l><"col-sm-2"B>>' + 'frtip', // Modified DOM for button alignment
            });
    
            t.on('order.dt search.dt', function() {
                t.on('draw.dt', function() {
                    var PageInfo = $('#inventory_table').DataTable().page.info();
                    t.column(0, { page: 'current' }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + PageInfo.start;
                    });
                });
            }).draw();
        });
    </script>
    
@endsection
