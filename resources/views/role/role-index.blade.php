@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Role Management</h3>
            <ul class="breadcrumbs mb-3">
             
               
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Roles</h4>
                            <a href="/role-create" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Add Role
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                       

                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Role Name</th>
                                        <th width="60%">Permissions</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                               
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                @foreach($role->permissions as $perm)
                                                    <span class="btn btn-info mr-1">
                                                        {{ $perm->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>

                                                <a class="edit_btn btn btn-link "
                                                    href="{{ route('admin.roles.edit.view', $role->id) }}"><i
                                                        class="fas fa-edit fa-lg"></i></a>
                                                <a class="delete_btn btn btn-link btn-danger "
                                                    href="{{ route('admin.roles.destroy', $role->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $role->id }}').submit();">
                                                    <i class="fas fa-trash"></i>


                                                </a>

                                                <form id="delete-form-{{ $role->id }}"
                                                    action="{{ route('admin.roles.destroy', $role->id) }}"
                                                    method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>

                                    </td>
                                    </tr>
                                    @endforeach
                                    
                                    
                                   
                                   
               
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DELETEMenuMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="DELETEMenuFORM" method="POST" enctype="multipart/form-data">

                {{ csrf_field() }}
                {{ method_field('DELETE') }}


                <div class="modal-body">
                    <input type="hidden" name="" id="id">
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
     $(document).ready(function () {
        $('#menu_table').DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
        });
    });
    $(document).on('click', '#close', function (e) {
        $('#EDITMenuMODAL').modal('hide');
    });

    $(document).on('click', '.cancel_btn', function (e) {
        $('#DELETEMenuMODAL').modal('hide');
    });
</script>

{{-- <script>
    $(document).ready(function() {



        var action =
            '<td> <div class="form-button-action"> 
                <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> 
                <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

   
    });
</script> --}}
@endsection
