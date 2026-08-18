<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'businesses' => Business::where('name', '!=', 'Super Admin')->orderBy('name')->get(),
            'roles' => Role::where('name', '!=', 'super-admin')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'business_id' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'business_id' => $validated['business_id'],
        ]);

        $user->assignRole($validated['role']);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully.',
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($user->hasRole('super-admin')) {
            return response()->json([
                'message' => 'Super admin cannot be edited.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'business_id' => [
                'required',
                'exists:businesses,id',
            ],

            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->business_id = $validated['business_id'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->syncRoles([
            $validated['role'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ]);
    }
}
