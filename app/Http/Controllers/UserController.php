<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
}
