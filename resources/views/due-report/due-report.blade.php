@extends('layouts.admin')

@section('content')
<div class="page-inner">
    <div class="page-header">
       
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Due Report</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="display table table-striped table-hover" id="due_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Invoice count</th>
                            <th>Total Amount</th>
                            <th>Total Paid</th>
                            <th>Total Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- JS will populate --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>







@endsection
@section('js')
    <script>
        // Show modal on click of buttton
     


    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });


        $(document).ready(function() {
            // Perform AJAX request to fetch data
            $.ajax({
                url: "/due-report-data",
                dataType: "json",
                success: function(data) {
                    // Initialize DataTable with fetched data
                    var t = $('#due_table').DataTable({
                        data: data.data, // Assuming data.category is the array containing your data
                        columns: [{
                                data: null
                            }, // Automatically assigned index
                            {
                                data: 'name'
                            },
                            {
                                data: 'mobile'
                            },
                            {
                                data: 'invoice_prices_count'
                            },
                            {
                                data: 'total_price'
                            },
                            {
                                data: 'total_paid'
                            },
                            {
                                data: 'total_due'
                            }
                           
                        ],
                        columnDefs: [{
                            searchable: true,
                            orderable: true,
                            targets: 0,
                        }, ],
                        order: [
                            [1, 'asc']
                        ],
                        pageLength: 10,
                        lengthMenu: [
                            [5, 10, 20, -1],
                            [5, 10, 20, 'Todos']
                        ],
                    });

                    // Add numbering to the first column
                    t.on('order.dt search.dt', function() {
                        t.on('draw.dt', function() {
                            var PageInfo = $('#due_table').DataTable().page.info();
                            t.column(0, {
                                page: 'current'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + PageInfo.start;
                            });
                        });
                    }).draw();
                },

            });
        });
      
    </script>
@endsection
