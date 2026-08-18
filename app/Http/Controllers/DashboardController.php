<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $payment = null;
        $showAlert = false;
        $daysLeft = 0;

        if ($user && !$user->hasRole('super-admin')) {

            $payment = DB::table('subscriptions')
                ->where('business_id', $user->business_id)
                ->latest('due_date')
                ->first();

            if ($payment) {
                $now = Carbon::now();
                $finalDate = Carbon::parse($payment->due_date)->addDays(5);
                $daysLeft = $now->diffInDays($finalDate, false);
                $showAlert = $daysLeft >= 0 && $daysLeft <= 8;
            }
        }

        return view('dashboard', compact(
            'payment',
            'showAlert',
            'daysLeft'
        ));
    }
}