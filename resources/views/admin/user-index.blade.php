@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">User Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tables</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
              
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title"> Add User </h4>
                            <a href="/create-user" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Add User
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                       

                        <div class="table-responsive">
                            <table id="menu_table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th style="width: 15%">Name</th>
                                        <th style="width: 15%">Email</th>
                                        <th style="width: 15%">Contact</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                               
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->contact_number }}</td>
                                        
                                <td>

                                    <a class="edit_btn btn btn-link btn-primary btn-lg"
                                        href="{{ route('admin.user.edit.view', $user->id) }}"> <i class="fa fa-edit"></i></a>

                                    <a class="delete_btn btn btn-link btn-danger"
                                        href="{{ route('admin.users.destroy', $user->id) }}"
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                        <i class="fas fa-trash "></i>
                                    </a>

                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('admin.users.destroy', $user->id) }}"
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
@endsection
@section('js')


<script>
        $(document).ready(function () {
            $('#menu_table').DataTable({
    pageLength: 10, // Default rows per page
    lengthMenu: [
        [5, 10, 20, -1], // Options: 5, 10, 20, or All
        [5, 10, 20, 'All'] // Display labels
    ],
    paging: true, // Enable paging
    searching: true, // Enable the search box
});

        });

</script>
@endsection
