@extends('layouts.admin')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Deposit Management</h3>
        <button class="btn btn-primary btn-round ms-auto" id="deposit_create">
            <i class="fa fa-plus"></i> Add Deposit
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Deposit List</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="display table table-striped table-hover" id="deposit_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ref ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Note</th>
                            <th>Action</th>
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

{{-- Create Deposit Modal --}}
<div class="modal fade" id="CreateDepositMODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <form id="AddDepositForm" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><strong>CREATE DEPOSIT</strong></h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-1">
                    <label>Reference ID <span class="text-danger">*</span></label>
                    <select name="sales_id" id="sales_id" class="form-control selectpicker border" data-live-search="true">
                        <option value="">Select Ref ID</option>
                        @foreach($refnumbers as $ref)
                            <option value="{{ $ref->id }}">{{ $ref->ref_id }}</option>
                        @endforeach
                    </select>
                    <h6 class="text-danger pt-1" id="wrongrefid" style="font-size: 14px;"></h6>
                </div>
                <div class="form-group mb-1">
                    <label>Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-control">
                    <h6 class="text-danger pt-1" id="wrongamount" style="font-size: 14px;"></h6>
                </div>
                <div class="form-group mb-1">
                    <label>Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-control">
                    <h6 class="text-danger pt-1" id="wrongdate" style="font-size: 14px;"></h6>
                </div>
                <div class="form-group mb-1">
                    <label>Note <span class="text-danger"></span></label>
                    <textarea  class="form-control" name="note" id="note" cols="30" rows="4"></textarea>
                    <h6 class="text-danger pt-1" id="wrongdate" style="font-size: 14px;"></h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Deposit Modal --}}
<div class="modal fade" id="EditDepositMODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="EditDepositForm" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <input type="hidden" name="depositid" id="depositid">
            <div class="modal-header">
                <h5 class="modal-title"><strong>UPDATE DEPOSIT</strong></h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Reference ID</label>
                    <select name="ref_id" id="edit_ref_id" class="form-control selectpicker" data-live-search="true" >
                        @foreach($refnumbers as $ref)
                            <option value="{{ $ref->id }}">{{ $ref->ref_id }}</option>
                        @endforeach
                    </select>
                    <h6 class="text-danger pt-1" id="edit_wrongrefid" style="font-size: 14px;"></h6>
                </div>
                <div class="form-group mb-3">
                    <label>Amount</label>
                    <input type="number" name="amount" id="edit_amount" class="form-control">
                    <h6 class="text-danger pt-1" id="edit_wrongamount" style="font-size: 14px;"></h6>
                </div>
                <div class="form-group mb-3">
                    <label>Date</label>
                    <input type="date" name="date" id="edit_date" class="form-control">
                    <h6 class="text-danger pt-1" id="edit_wrongdate" style="font-size: 14px;"></h6>
                </div>
                   <div class="form-group mb-3">
                    <label>Note</label>
                    <textarea name="edit_note" id="edit_note" cols="30" rows="4" class="form-control"></textarea>
                    <h6 class="text-danger pt-1" id="edit_wrongdate" style="font-size: 14px;"></h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Deposit Modal --}}
<div class="modal fade" id="DeleteDepositMODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="DeleteDepositForm" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <input type="hidden" name="depositid" id="delete_depositid">
            <div class="modal-body">
                <h5 class="text-center">Are you sure you want to delete this deposit?</h5>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('js')
    <script>
        // Show modal on click of buttton
        $('#deposit_create').click(function(e) {
            e.preventDefault();
            $('#CreateDepositMODAL').modal('show');
        });


      $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // CREATE DEPOSIT
    $(document).on('submit', '#AddDepositForm', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('ref_id', $('#sales_id option:selected').text());

        $.ajax({
            type: "POST",
            url: "/deposit-create",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
            console.log(response)
                if ($.isEmptyObject(response.error)) {
                    $.notify('Deposit added successfully!', {
                        className: 'success',
                        position: 'top right'
                    });
                    $('#AddDepositForm')[0].reset();
                    $('.selectpicker').selectpicker('refresh');
                    $('#CreateDepositMODAL').modal('hide');
                    window.location.reload();
                } else {
                    printDepositErrors(response.error);
                }
            }
        });
    });

    function printDepositErrors(message) {
        $('#wrongrefid').empty();
        $('#wrongamount').empty();
        $('#wrongdate').empty();

        $('#wrongrefid').text(message.ref_id ? message.ref_id[0] : '');
        $('#wrongamount').text(message.amount ? message.amount[0] : '');
        $('#wrongdate').text(message.date ? message.date[0] : '');
    }

    // EDIT DEPOSIT
    $(document).on('click', '.edit_deposit_btn', function (e) {
        e.preventDefault();
        const depositId = $(this).val();
        $('#EditDepositMODAL').modal('show');

        $.ajax({
            type: "GET",
            url: "/deposit-edit/" + depositId,
            success: function (response) {
                if (response.status == 200) {
                    const deposit = response.deposit;
                    $('#depositid').val(deposit.id);
                //    $('#edit_ref_id').val(deposit.sales_id).selectpicker('refresh').prop('disabled', true);
                $('#edit_ref_id').val(deposit.sales_id);               // Set the value
$('#edit_ref_id').prop('disabled', true);            // Disable the select
$('#edit_ref_id').selectpicker('refresh');           // Refresh the selectpicker after disabling


                    $('#edit_amount').val(deposit.amount);
                    $('#edit_date').val(deposit.date);
                    $('#edit_note').val(deposit.note);
                }
            }
        });
    });

    // UPDATE DEPOSIT
    $(document).on('submit', '#EditDepositForm', function (e) {
        e.preventDefault();

        const id = $('#depositid').val();
        let EditFormData = new FormData(this);
        EditFormData.append('_method', 'PUT');

        $.ajax({
            type: "POST",
            url: "/deposit-edit/" + id,
            data: EditFormData,
            contentType: false,
            processData: false,
            success: function (response) {
                if ($.isEmptyObject(response.error)) {
                    $.notify('Deposit updated successfully!', {
                        className: 'success',
                        position: 'top right'
                    });
                    $('#EditDepositForm')[0].reset();
                    $('.selectpicker').selectpicker('refresh');
                    $('#EditDepositMODAL').modal('hide');
                    window.location.reload();
                } else {
                    $('#edit_wrongrefid').empty().text(response.error.ref_id ? response.error.ref_id[0] : '');
                    $('#edit_wrongamount').empty().text(response.error.amount ? response.error.amount[0] : '');
                    $('#edit_wrongdate').empty().text(response.error.date ? response.error.date[0] : '');
                }
            }
        });
    });

    // DELETE DEPOSIT
    $('#deposit_table').on('click', '.delete_btn', function () {
        const depositId = $(this).data("value");
        $('#delete_depositid').val(depositId);
        $('#DeleteDepositForm').attr('action', '/deposit-delete/' + depositId);
        $('#DeleteDepositMODAL').modal('show');
    });

    // Flash Message Display
    @if (session("status"))
        $.notify('{{ session("status") }}', {
            className: 'success',
            position: 'top right'
        });
    @endif

    @if (session("error"))
        $.notify('{{ session("error") }}', {
            className: 'error',
            position: 'top right'
        });
    @endif
});


        $(document).ready(function() {
            // Perform AJAX request to fetch data
            $.ajax({
                url: "/deposit-list-data",
                dataType: "json",
                success: function(data) {
                    // Initialize DataTable with fetched data
                    var t = $('#deposit_table').DataTable({
                        data: data.deposits, // Assuming data.category is the array containing your data
                        columns: [{
                                data: null
                            }, // Automatically assigned index
                            {
                                data: 'ref_id'
                            },
                            {
                                data: 'customer.name'
                            },
                            {
                                data: 'amount'
                            },
                            {
                                data: 'date'
                            },
                             {
                                data: 'note'
                            },
                            {
                                render: getBtns
                            },
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
                            var PageInfo = $('#deposit_table').DataTable().page.info();
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
        // load bottons in Datatable
        function getBtns(data, type, row, meta) {
            var id = row.id;
            return '<button type="button" value="' + id + '" class="edit_deposit_btn btn btn-link btn-primary btn-lg"><i class="fas fa-edit fa-lg"></i></button>\
                <a href="javascript:void(0)" class="delete_btn btn btn-link btn-danger " data-value="' + id +
                '"><i class="fas fa-trash fa-lg"></i></a>';
        }
    </script>
@endsection
