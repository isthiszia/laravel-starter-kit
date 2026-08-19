<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:subscription')->only('index');
        $this->middleware('permission:add-subscription')->only('store');
    }
    public function index()
    {
        return view('subscription.index', [
            'businesses' => Business::where('name', '!=', 'Super Admin')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
        ]);

        $today = now();

        $lastSubscription = Subscription::where('business_id', $request->business_id)
            ->orderBy('due_date', 'desc')
            ->first();

        if ($lastSubscription) {
            $startDate = Carbon::parse($lastSubscription->due_date);
        } else {
            $startDate = $today;
        }

        $dueDate = $startDate->copy()->addMonth();

        Subscription::create([
            'business_id' => $request->business_id,
            'month' => $startDate->format('Y-m'),
            'status' => 'active',
            'start_date' => $startDate,
            'due_date' => $dueDate,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Subscription activated successfully.',
        ]);
    }
}
