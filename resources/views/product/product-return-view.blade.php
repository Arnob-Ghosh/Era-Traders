@extends('layouts.admin')

<!-- Bootstrap Select CSS -->

    
@section('content')
    <div class="page-inner">
        <div class="page-header">
         
    </div>
   
    

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Product In Return</h4>

                        </div>
                    </div>
                    <div class="card-body">
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
                    url: "/product-in-return-report-data",
                    dataSrc: 'data',
                    dataType: "json",
                },
                columns: [
                    {
                        data: null,
                        title: '#'
                    },
                    {
                        data: 'date',
                        title: 'Date'
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
                        title: 'Return Quantity',
                        render: function(data, type, row) {
                            return row.quantity + ' - ' + row.unit;
                        }
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
