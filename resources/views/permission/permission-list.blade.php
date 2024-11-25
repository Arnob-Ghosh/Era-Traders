@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Permission List</h3>
            <ul class="breadcrumbs mb-3">
             
               
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Add Row</h4>
                           <a href="/permission-create" class="btn btn-primary btn-round ms-auto" >
                                <i class="fa fa-plus"></i>
                                Add Row
                           </a>
                        </div>
                    </div>
                    <div class="card-body">
                       

                        <div class="table-responsive">
                            <table id="permission_table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                            <th>Route Name</th>
                                            <th>Permission Name</th>
                                            <th>Permission Group</th>
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
    <!-- Edit Vat Modal -->
<div class="modal fade" id="EDITPermissionMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE PERMISSION</strong></h5>	        
		</div>
  
  
		<!-- Update Vat Form -->
		<form id="UPDATEPermissionFORM" enctype="multipart/form-data">
			
			<input type="hidden" name="_method" value="PUT">
			  <input type="hidden" name="_token" value="{{ csrf_token() }}">
			
			<div class="modal-body">
  
				<input type="hidden" name="id" id="id">
  
				<div class="form-group mb-3">
					<label>Permission Name<span class="text-danger"><strong>*</strong></span></label>
					<input type="text" id="edit_permission_name" name="permission_name" class="form-control">
						  <h6 class="text-danger pt-1" id="edit_wrong_permission_name" style="font-size: 14px;"></h6>
  
				</div>
  
				<div class="form-group mb-3">
					<label>Permission Group Name <span class="text-danger"><strong>*</strong></span></label><br>
				   <select class="form-select" data-width="100%"  name="permission_group" id="edit_permission_group">
					@foreach($permission_groups as $permission_group)
						<option value="{{$permission_group->group_name}}" >{{$permission_group->group_name}}</option>
					@endforeach
				   </select> 
				</div>

				<div class="form-group mb-3">
					<label>Permission route name <span class="text-danger"><strong>*</strong></span></label>
					<input type="test" id="edit_route_name" name="route_name" class="form-control">
						  <h6 class="text-danger pt-1" id="edit_wrong_permission_group" style="font-size: 14px;"></h6>
  
				</div>

				<div class="form-group mb-3">
					<label>Permission Type <span class="text-danger"><strong>*</strong></span></label>
				   <select class="form-control"  name="permission_group_type" id="edit_permission_group_type">
					<option value="create">Create</option>
					<option value="edit">Edit</option>
					<option value="view">View</option>
					<option value="destroy">Delete</option>
				   </select> 
				</div>

						   
		  </div>
  
		  <div class="modal-footer">
			  <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  <button type="submit" class="btn btn-primary">Update</button>
		  </div>
		</form>
		<!-- End Update Vat Form -->
  
	  </div>
	</div>
  </div>
  <!-- End Edit Vat Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEPermissionMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEPermissionFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="vatid"> 
			      <h5 class="text-center">Are you sure you want to delete?</h5>
			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel btn btn-secondary cancel_btn" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-danger">Yes</button>
			    </div>

			</form>

		</div>
	</div>
</div>
@endsection
@section('js')


<script>


$('#close').click(function (e) {
            e.preventDefault();
            $('#EDITPermissionMODAL').modal('hide');

        });
        $('.cancel_btn').click(function (e) {
            e.preventDefault();

            $('#DELETEPermissionMODAL').modal('hide');
        });


        $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    //CREATE Permission
    $(document).on("submit", "#AddPermissionForm", function (e) {
        e.preventDefault();

        let formData = new FormData($("#AddPermissionForm")[0]);

        $.ajax({
          
            type: "POST",
            url: "/permission-create",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $(location).attr("href", "/permission-list");
                if ($.isEmptyObject(response.error)) {
                    $(location).attr("href", "/permission-list");
                } else {
                    // 
                    printErrorMsg(response.error);
                }
            },
        });
    });

    function printErrorMsg(message) {
        $("#wrong_permission_name").empty();
        $("#wrong_permission_group").empty();

        if (message.permission_name == null) {
            permission_name = "";
        } else {
            permission_name = message.name[0];
        }
        if (message.permission_group == null) {
            permission_group = "";
        } else {
            permission_group = message.group[0];
        }

        $("#wrong_permission_name").append('<span id="">' + name + "</span>");
        $("#wrong_permission_group").append(
            '<span id="">' + group_name + "</span>"
        );

    }
});
// PERMISSION LIST
$(document).ready(function () {
    var t = $('#permission_table').DataTable({
        ajax: {
            "url": "/permission-list-data",
            "dataSrc": "permissions",
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'permissions_name' },
            { data: 'group_name' },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<button type="button" value="' +row.id +'" class="edit_btn btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></button>\
                    <a href="javascript:void(0)" class="delete_btn btn btn-link btn-danger " data-value="' + row.id +'"><i class="fa fa-trash"></i></a>'
                } 
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
            var PageInfo = $('#permission_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();


});

//EDIT Permission
$(document).on("click", ".edit_btn", function (e) {
    e.preventDefault();

    var Id = $(this).val();
    $("#EDITPermissionMODAL").modal("show");

    $.ajax({
        type: "GET",
        url: "/permission-edit/" + Id,
        success: function (response) {
            if (response.status == 200) {
                $("#edit_permission_name").val(
                    response.permission.permissions_name
                );
                $("#id").val(response.permission.id);
                $("#edit_route_name").val(response.permission.name);
                $("#edit_permission_group").val(response.permission.group_name);
                $("#edit_permission_group_type").val(response.permission.permission_type);
                
                var newOption = $(
                    '<option value="' +
                        response.permission.group_name +
                        '">' +
                        response.permission.group_name +
                        "</option>"
                        
                );


                $("#edit_route_name").val(response.permission.name);
               
            }
        },
    });
});

//UPDATE Permission
$(document).on("submit", "#UPDATEPermissionFORM", function (e) {
    e.preventDefault();

    var id = $("#id").val();

    let EditFormData = new FormData($("#UPDATEPermissionFORM")[0]);

    EditFormData.append("_method", "PUT");

    $.ajax({
     
        type: "POST",
        url: "/permission-edit/" + id,
        data: EditFormData,
        contentType: false,
        processData: false,
        success: function (response) {
            if ($.isEmptyObject(response.error)) {
                $("#EDITVatMODAL").modal("hide");
                $.notify(response.message, "success");
                $(location).attr("href", "/permission-list");
            } else {
                $("#wrong_permission_name").empty();
                $("#wrong_permission_group").empty();
            }
        },
    });
});

//Delete Vat
$(document).ready(function () {
    $("#permission_table").on("click", ".delete_btn", function () {
        var Id = $(this).data("value");

        $("#id").val(Id);

        $("#DELETEPermissionFORM").attr("action", "/permission-delete/" + Id);

        $("#DELETEPermissionMODAL").modal("show");
    });
});


function resetButton() {
    $("form").on("reset", function () {
       
    });
}

  
</script>
@endsection
