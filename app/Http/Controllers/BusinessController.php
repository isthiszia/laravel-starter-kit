<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;

class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:business')->only('index');
        $this->middleware('permission:add-business')->only('store');
        $this->middleware('permission:edit-business')->only('update');
    }
    public function index()
    {
        return view('business.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:businesses,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        Business::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Business created successfully.',
        ]);
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('businesses', 'email')
                    ->ignore($business->id),
            ],

            'phone' => [
                'required',
                'string',
                'max:50',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        $business->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Business updated successfully.',
        ]);
    }
}
