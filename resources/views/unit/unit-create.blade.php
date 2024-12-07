@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Unit Management</h3>
            <button class="btn btn-primary btn-round ms-auto" id="unit_create"> <i class="fa fa-plus"></i> Add
                Unit</button>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Unit Management</h4>

                </div>
            </div>
            <div class="card-body">

                <div class="table-respponsive ">
                    {{-- ClienT Table --}}
                    <table class="display table table-striped table-hover" id="unit_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Unit Name</th>
                                <th>Description</th>
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

    <div class="modal fade" id="CreateUnitMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>CREATE UNIT</strong></h5>
                </div>

                <form id="AddUnitForm" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-body">
                   
                        <div class="form-group">
                            <label for="unitname" class="form-label" style="font-weight: normal;">Unit Name<span class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control w-50" name="unitname" id="unitname" placeholder="e.g. mg, gm, kg">
                            <h6 class="text-danger pt-1" id="wrongunitname" style="font-size: 14px;"></h6>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label" style="font-weight: normal;">Description</label>
                            <textarea class="form-control w-50" name="description" id="description" rows="3" placeholder="any description for the unit"></textarea>
                        </div>
                        

                      
                            
                      

                    </div>
                    <div class="modal-footer">
                        <button id="close" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Edit Unit Modal -->
<div class="modal fade" id="EDITUnitMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE UNIT</strong></h5>	        
        </div>
  
  
        <!-- Update Unit Form -->
        <form id="UPDATEUnitFORM" enctype="multipart/form-data">
            
            <input type="hidden" name="_method" value="PUT">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div class="modal-body">
  
                <input type="hidden" name="unitid" id="unitid">
  
                <div class="form-group mb-3">
                    <label>Unit Name<span class="text-danger"><strong>*</strong></span></label>
                    <input type="text" id="edit_unitname" name="unitname" class="form-control">
                          <h6 class="text-danger pt-1" id="edit_wrongunitname" style="font-size: 14px;"></h6>
                </div>
  
                <div class="form-group mb-3">
                    <label>Description</label>
                    <textarea class="form-control w-50" name="description" id="edit_description" rows="3" placeholder="update description for the unit"></textarea>
                </div>
                           
              </div>
              <div class="modal-footer">
                  <button id="closes" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Update</button>
              </div>
        </form>
        <!-- End Update Unit Form -->
  
      </div>
    </div>
  </div>
  <!-- End Edit Unit Modal -->
  
  <!-- Delete Modal --> 
  
  <div class="modal fade" id="DELETEUnitMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
  
              <form id="DELETEUnitFORM" method="POST" enctype="multipart/form-data">
  
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                  
  
                  <div class="modal-body"> 
                      <input type="hidden" name="" id="unitid"> 
                    <h5 class="text-center">Are you sure you want to delete?</h5>
                  </div>
  
                  <div class="modal-footer justify-content-center">
                      <button type="button" class="cancel_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="delete btn btn-outline-danger">Yes</button>
                  </div>
  
              </form>
  
          </div>
      </div>
  </div>
  
  <!-- END Delete Modal -->
@endsection
@section('js')
   <script>
        $('#close').click(function(e) {
            e.preventDefault();

            $('#CreateUnitMODAL').modal('hide');
        });
        $('#closes').click(function(e) {
            e.preventDefault();

            $('#EDITUnitMODAL').modal('hide');

        });
        $('.cancel_btn').click(function(e) {
            e.preventDefault();

            $('#DELETEUnitMODAL').modal('hide');
        });



        $(document).ready(function () {
// Function to show the modal for creating a new unit
    $('#unit_create').click(function (e) {
        e.preventDefault();
        $('#CreateUnitMODAL').modal('show');
    }); 

    // AJAX submission for creating a new unit
	$(document).on('submit', '#AddUnitForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddUnitForm')[0]);

		$.ajax({
			type: "POST",
			url: "/unit-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				if($.isEmptyObject(response.error)){
					$('#AddUnitForm')[0].reset();

             		window.location.reload();

                }else{
                    printErrorMsg(response.error);
                }
			}
		});
	});
  // Function to print error messages for unit creation form
	function printErrorMsg (message) {
            $('#wrongunitname').empty();

			if(message.unitname == null){
				unitname = ""
			}else{
				unitname = message.unitname[0]
			}

            $('#wrongunitname').append('<span id="">'+unitname+'</span>');
    }
});

   // DataTable initialization for unit list
$(document).ready(function () {
    var t = $('#unit_table').DataTable({
        ajax: {
            "url": "/unit-list-data",
            "dataSrc": "unit",
        },
        columns: [
          	{ data: null },
            { data: 'name' },
            { "render": function ( data, type, row, meta ){
            		if(row.description == null){
						var description = 'N/A'
					}else{
						var description = row.description
					}
            		return description
	            }
	        },
            { "render": function ( data, type, row, meta ){

            		return '<button type="button" value="'+row.id+'" class="edit_btn btn btn-link "><i class="fas fa-edit fa-lg"></i></button>\
                    	<a href="javascript:void(0)" class="delete_btn btn btn-danger btn-link " data-value="'+row.id+'"><i class="fas fa-trash la-lg"></i></a>'
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
        order: [[1, 'desc']],
        pageLength : 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });

 // Update table indices after ordering or searching
    t.on('order.dt search.dt', function () {

	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#unit_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();

});

    // Function to handle editing a unit
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var unitId = $(this).val();

	$('#EDITUnitMODAL').modal('show');

		$.ajax({
		type: "GET",
		url: "/unit-edit/"+unitId,
		success: function(response){
			if (response.status == 200) {
				$('#edit_unitname').val(response.unit.name);
				$('#edit_description').val(response.unit.description);
				$('#unitid').val(unitId);
			}
		}
	});
});
    // Function to handle updating a unit
$(document).on('submit', '#UPDATEUnitFORM', function (e)
{
	e.preventDefault();

	var id = $('#unitid').val();

	let EditFormData = new FormData($('#UPDATEUnitFORM')[0]);

	EditFormData.append('_method', 'PUT');

	$.ajax({
		type: "POST",
		url: "/unit-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){

			if($.isEmptyObject(response.error)){

                $('#EDITUnitMODAL').modal('hide');
				$('#UPDATEUnitFORM')[0].reset();

                window.location.reload();

            }else{
                $('#edit_wrongunitname').empty();

				if(response.error.unitname == null){
					unitname = ""
				}else{
					unitname = response.error.unitname[0]
				}

                $('#edit_wrongunitname').append('<span id="">'+unitname+'</span>');


            }
		}
	});
});


// Function to handle deleting a unit
$(document).ready(function () {
    $("#unit_table").on("click", ".delete_btn", function () {
        var unitId = $(this).data("value");

        $("#unitid").val(unitId);

        $("#DELETEUnitFORM").attr("action","/unit-delete/" + unitId
        );

        $("#DELETEUnitMODAL").modal("show");
    });
});
    </script>
@endsection
