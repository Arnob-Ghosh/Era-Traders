@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Category Management</h3>
            <button class="btn btn-primary btn-round ms-auto" id="category_create"> <i class="fa fa-plus"></i> Add
                Category</button>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Category Management</h4>

                </div>
            </div>
            <div class="card-body">

                <div class="table-respponsive ">
                    {{-- ClienT Table --}}
                    <table class="display table table-striped table-hover" id="category_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="CreateCategoryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>CREATE CATEGORY</strong></h5>
                </div>


                <!-- Create Category Form -->
                <form id="AddCategoryForm" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="modal-body">


                        <div class="form-group mb-3">
                            <label class="form-label">Category Name<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" id="categoryname" name="categoryname"
                                class="form-control form-control-outline">
                            <div id="" class="form-text"><strong>N.B. </strong>Be sure to make your category name
                                meaningful.</div>
                            <h6 class="text-danger pt-1" id="wrongcategoryname" style="font-size: 14px;"></h6>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button id="close" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
                <!-- End Create Category Form -->

            </div>
        </div>
    </div>

    <div class="modal fade" id="EDITCategoryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE CATEGORY</strong></h5>
                </div>


                <!-- Update Category Form -->
                <form id="UPDATECategoryFORM" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="modal-body">

                        <input type="hidden" name="categoryid" id="categoryid">

                        <div class="form-group mb-3">
                            <label class="form-label">Category Name<span
                                    class="text-danger"><strong>*</strong></span></label>
                            <input type="text" id="edit_categoryname" name="categoryname" class="form-control">
                            <div id="" class="form-text"><strong>N.B. </strong>Be sure to make your category name
                                meaningful.</div>
                            <h6 class="text-danger pt-1" id="edit_wrongcategoryname" style="font-size: 14px;"></h6>
                        </div>
                    
                    </div>
                    <div class="modal-footer">
                        <button id="closes" type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
                <!-- End Update Category Form -->

            </div>
        </div>
    </div>

    <div class="modal fade" id="DELETECategoryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form id="DELETECategoryFORM" method="POST" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}


                    <div class="modal-body">
                        <input type="hidden" name="" id="categoryid">
                        <h5 class="text-center">Are you sure you want to delete?</h5>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="cancel btn btn-secondary cancel_btn"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="delete btn btn-danger">Yes</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // Show modal on click of buttton
        $('#category_create').click(function(e) {
            e.preventDefault();
            $('#CreateCategoryMODAL').modal('show');
        });


        // Send form data to backend
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            //CREATE CATEGORY
            $(document).on('submit', '#AddCategoryForm', function(e) {
                e.preventDefault();

                let formData = new FormData($('#AddCategoryForm')[0]);

                $.ajax({
                    type: "POST",
                    url: "/category-create",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if ($.isEmptyObject(response.error)) {
                            $.notify('Success', {
                                className: 'success',
                                position: 'top right'
                            })
                            $('#AddCategoryForm')[0].reset();

                            window.location.reload();
                        } else {

                            printErrorMsg(response.error);
                        }
                    }
                });

            });
            // show customised error messege
            function printErrorMsg(message) {

                $('#wrongcategoryname').empty();

                if (message.categoryname == null) {
                    categoryname = ""
                } else {
                    categoryname = message.categoryname[0]
                }

                $('#wrongcategoryname').append('<span id="">' + categoryname + '</span>');

            }

        });



        //EDIT CATEGORY
        $(document).on('click', '.edit_btn', function(e) {
            e.preventDefault();

            var categoryId = $(this).val();
            $('#EDITCategoryMODAL').modal('show');

            $.ajax({
                type: "GET",
                url: "/category-edit/" + categoryId,
                success: function(response) {

                    if (response.status == 200) {
                        $('#edit_categoryname').val(response.category.category_name);
                        $('#categoryid').val(categoryId);
                    }
                }
            });
        });


        //UPDATE CATEGORY
        $(document).on('submit', '#UPDATECategoryFORM', function(e) {
            e.preventDefault();

            var id = $('#categoryid').val();

            let EditFormData = new FormData($('#UPDATECategoryFORM')[0]);

            EditFormData.append('_method', 'PUT');

            $.ajax({
                type: "POST",
                url: "/category-edit/" + id,
                data: EditFormData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if ($.isEmptyObject(response.error)) {
                        $('#EDITCategoryMODAL').modal('hide');
                        $('#UPDATECategoryFORM')[0].reset();

                        window.location.reload();

                    } else {
                        $('#edit_wrongcategoryname').empty();

                        if (response.error.categoryname == null) {
                            categoryname = ""
                        } else {
                            categoryname = response.error.categoryname[0]
                        }

                        $('#edit_wrongcategoryname').append('<span id="">' + categoryname + '</span>');

                    }
                }
            });
        });


        // DELETE CATEGORY
        $(document).ready(function() {
            $('#category_table').on('click', '.delete_btn', function() {

                var categoryId = $(this).data("value");

                $('#categoryid').val(categoryId);

                $('#DELETECategoryFORM').attr('action', '/category-delete/' + categoryId);

                $('#DELETECategoryMODAL').modal('show');

            });
        });

        $(document).ready(function() {
            // Perform AJAX request to fetch data
            $.ajax({
                url: "/category-list-data",
                dataType: "json",
                success: function(data) {
                    // Initialize DataTable with fetched data
                    var t = $('#category_table').DataTable({
                        data: data
                        .category, // Assuming data.category is the array containing your data
                        columns: [{
                                data: null
                            }, // Automatically assigned index
                            {
                                data: 'category_name'
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
                            var PageInfo = $('#category_table').DataTable().page.info();
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
            return '<button type="button" value="' + id + '" class="edit_btn btn btn-link btn-primary btn-lg"><i class="fas fa-edit fa-lg"></i></button>\
                <a href="javascript:void(0)" class="delete_btn btn btn-link btn-danger " data-value="' + id +
                '"><i class="fas fa-trash fa-lg"></i></a>';
        }
    </script>
@endsection
