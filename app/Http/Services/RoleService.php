<?php

namespace App\Http\Services;

use Log;    
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleService
{
    // Function to display the list of roles
    public function index()
    {
        // Retrieve roles that belong to the currently authenticated user's subscriber
        $roles = Role::get();
        // Return the view for displaying roles with the retrieved roles
        return view('role.role-index', compact('roles'));
    }

    // Function to display the create role form
    public function create()
    {
        // Retrieve all permissions
        $permissions = Permission::all();

        // Retrieve distinct permission groups
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();

        // Return the view for creating a role with the retrieved permissions and permission groups
        return view('role.role-create', compact('permissions', 'permission_groups'));
    }

    // Function to store a new role
    public function store(Request $request)
    {
        // Custom validation error messages
        $messages = [
            'rolename.required' => "Role name is required.",
        ];

        // Validator for role name
        $validator = Validator::make($request->all(), [
            'rolename' => 'required',
        ], $messages);

        // If validation passes
        if ($validator->passes()) {
            // Create a new role and set its attributes
            $role = new Role();
            $role->name = $request->rolename;
            $role->save();

            // Sync the role's permissions if provided
            $permissions = $request->input('permissions');
            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }
            // Redirect to the roles list
            return redirect()->route('admin.roles');
        }

        // Return validation errors if validation fails
        return response()->json(['error' => $validator->errors()]);
    }

    // Function to display the edit role form
    public function edit($id)
    {
        // Find the role by ID
        $role = Role::findById($id);
        // Retrieve distinct permission groups
        $permissions = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();

        // Return the view for editing a role with the retrieved role and permissions
        return view('role.role-edit', compact('role', 'permissions'));
    }

    // Function to update an existing role
    public function update(Request $request, $id)
    {
        // Find the role by ID and update its name
        $role = Role::find($id);
        $role->name = $request->rolename;

        // Sync the role's permissions if provided
        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        $role->save();

        // Flash success message and redirect to the roles list
        session()->flash('success', 'Role has been updated !!');
        return redirect()->route('admin.roles');
    }

    // Function to delete a role
    public function destroy($id)
    {
        // Find the role by ID
        $roleid = Role::find($id);

        // Prevent deletion of the 'admin' role
        if ($roleid->name == 'admin') {
            return redirect('/role-list');
        } else {
            // Delete the role and flash success message
            $roleid->delete();
            session()->flash('success', 'Role has been deleted !!');
            return back();
        }
    }
}
