@extends('layouts.admin')

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
                            <h4 class="card-title">Add Product</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                            data-bs-target="#CreateProductMODAL"><i class="fa fa-plus"></i>Add Product</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        

                        <div class="table-responsive">
                            <table id="product_table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Brand</th>
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
        </div>
    </div>

    <div class="modal fade" id="DELETEProductMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form id="DELETEProductFORM" method="POST" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}


                    <div class="modal-body">
                        <input type="hidden" name="" id="productid">
                        <h5 class="text-center">Are you sure you want to delete?</h5>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary cancel_btn"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="delete btn btn-outline-danger">Yes</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="CreateProductMODAL" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel"aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>CREATE PRODUCT</strong></h5>
            </div>
            {{-- Create Client --}}
            <form id="AddProductForm" enctype="multipart/form-data">

                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body mb-2">

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="productname" class="form-label"
                                    style="font-weight: normal;">Product Name<span
                                        class="text-danger"><strong>*</strong></span></label>
                                <input style="width:70%" class="form-control " type="text"
                                    name="productName" id="productname" placeholder="e.g. Napa">
                                <h6 class="text-danger pt-1" id="wrongproductname"
                                    style="font-size: 14px;">
                                </h6>

                            </div>
                        </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="brandname" class="form-label" style="font-weight: normal;">Brand<span class="text-danger"><strong>*</strong> </span></label><br>
                                    <input style="width:70%" class="form-control " type="text"
                                    name="brand" id="brand" >
                                    <h6 class="text-danger pt-1" id="wrongbrandname"
                                        style="font-size: 14px;"></h6>
                                </div>
                            </div>

                    </div>
                 
                   
                 
                   
                </div>

                <div class="modal-footer mt-2">
                    <button id="close" type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
@section('js')


<script>
    $(document).ready(function () {
    var t = $('#product_table').DataTable({
        ajax: {
        	headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
            "url": "/product-list-data",
            "dataSrc": "products",
            "dataType": "json",
        },
        columns: [
          	{ data: null },

            { 
            	data: 'productName'
            },
            { 
                data: 'brand'
            },
            { 
                data: 'id',
                render: getBtns
            },
        ],
        columnDefs: [
            {
                searchable: true,
                orderable: true,
                targets: 0,
            },
        ],
        order: [[1, 'asc']],
        pageLength : 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });


    t.on('order.dt search.dt', function () {

	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();


});
function getBtns(data, type, row, meta) {

var id = row.id;
return '<a title="Edit" type="button" class="edit_btn btn btn-link btn-primary btn-lg "><i class="fa fa-edit "></i></a>\
        <a href="javascript:void(0)" class="delete_btn btn-link btn-danger" data-value="'+id+'"><i class="fa fa-trash"></i></a>';
}

$(document).ready( function () {
    $('#product_table').on('click', '.delete_btn', function(){

        var product_id = $(this).data("value");

        $('#productid').val(product_id);
        $('#DELETEProductFORM').attr('action', '/product-delete/'+product_id);
        $('#DELETEProductMODAL').modal('show');

    });
});
$(document).on('submit', '#AddProductForm', function (e) {
    e.preventDefault();

    let formData = new FormData($('#AddProductForm')[0]);

        $.ajax({
            
            type: "POST",
            url: "/product-create",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
            success: function(response){
                if($.isEmptyObject(response.error)){
	        	$(location).attr('href','/product-create');


                }else{
                    printErrorMsg(response.error);
                }
                
            }
        });
});
function printErrorMsg (message) {

$('#wrongproductname').empty();
$('#wrongbrandname').empty();
if(message.productName == null){
    productName = ""
}else{
    productName = message.productName[0]
}
if(message.brand == null){
    brand = ""
}else{
    brand = message.brand[0]
}


$('#wrongproductname').append('<span id="">'+productName+'</span>');
$('#wrongbrandname').append('<span id="">'+brand+'</span>');
}
   
</script>
@endsection
