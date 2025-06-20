<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Log;    

class UserController extends Controller
{
    //
  
    public function regUser()
    {
        // Fetch roles and stores associated with the authenticated user's subscriber_id
        $roles  = Role::get();
        // Return the view with the fetched roles and stores data
        return view('admin.create-user',compact('roles'));
    }

    // Handle the registration of a new user
    public function storeUser(Request $request)
    {
        // Custom validation error messages
        $messages = [
            'name.required' => "Name is required.",
            'name.max' => "Max 255 characters.",
            'email.required' => "Email is required.",
            'email.email' => "Email is not valid.",
            'email.max' => "Max 255 characters.",
            'email.unique' => "Email already exists.",
            'contactnumber.required' => "Contact number is required.",
            'roles.required' => "Role is required.",
            'password.required' => "Password is required.",
            'password.confirmed' => "Confirm your password or password does not match.",
        ];
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'contactnumber' => ['required'],
            'roles' => ['required'],
            // 'store' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], $messages);
        // If validation passes, create the user and assign roles
        if ($validator->passes()) {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'contact_number' => $request->contactnumber,
                'image' => $request->hasFile('image') ? $request->file('image')->store('users', 'public') : null,
            ]);

            // Assign the roles to the user
            if ($request->roles) {
                $user->assignRole($request->roles);
            }
            // Trigger the Registered event
            event(new Registered($user));
            // Redirect to the roles management page
            return redirect()->route('admin.user.list');
        }
        // Return validation errors as JSON response
        return response()->json(['error' => $validator->errors()]);
    }
    // Display the list of users and roles
    public function userList()
    {
        // Fetch roles and users associated with the authenticated user's subscriber_id
        $roles  = Role::get();
        $users = User::get();

        // Return the view with the fetched users and roles data
        return view('admin.user-index', compact('users','roles'));
    }

    // Display the user edit form
    public function userEdit(Request $request, $id)
    {
        // Find the user by ID
        $user = User::find($id);
        $user_roles = $user->getRoleNames();
        $roles  = Role::get();

        
        if ($request->ajax()) {
            return response()->json([
                'status' => 200,
                'user' => $user,
                'user_roles' => $user_roles

            ]);
        }
        // Return the view with the fetched user, roles, and stores data
        return view('admin.user-edit', compact('user','roles'));
    }

    // Handle the update of an existing user
    public function userUpdate(Request $request, $id)
    {
        // Find the user by ID
        $user = User::find($id);
        // Custom validation error messages
        $messages = [
            'name.required' => "Name is required.",
            'name.max' => "Max 255 characters.",
            'email.required' => "Email is required.",
            'email.email' => "Email is not valid.",
            'email.max' => "Max 255 characters.",
            'contactnumber.required' => "Contact number is required.",
            'password.confirmed' => "Confirm your password or password does not match.",
        ];
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contactnumber' => ['required'],
            'password' => ['confirmed'],
        ], $messages);
        // If validation passes, update the user data
        if ($validator->passes()) {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact_number = $request->contactnumber;
            if ($request->password) {
                $user->password = $request->password;
            }
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete previous image if it exists
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $path = $request->file('image')->store('users', 'public');
                $user->image = $path;
            }
            $user->save();
            // Detach and re-assign roles to the user 
            if ($request->roles) {
                $user->roles()->detach();
                $user->assignRole($request->roles);
            }

            // Return success message as JSON response
            return response()->json([
                'status' => 200,
                'message' => 'user updated successfully!',
            ]);
            // Redirect to the roles management page
            return redirect()->route('admin.roles');
        }
        // Return validation errors as JSON response
        return response()->json(['error' => $validator->errors()]);
    }

    // Handle the deletion of a user
    public function userDestroy($id)
    {
        // Find the user by ID
        $user = User::find($id);
        
        // If user exists, delete image and user
        if (!is_null($user)) {
            // Delete user's image if it exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->delete();
        }

        // Return success response
        return redirect()->back()->with('status', 'User has been deleted !!');
    }
}
