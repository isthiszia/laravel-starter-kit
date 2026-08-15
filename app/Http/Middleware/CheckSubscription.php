<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        if (!$user->business_id) {
            abort(403, 'No business is associated with this account.');
        }

        $today = now()->startOfDay();

        $subscription = Subscription::where('business_id', $user->business_id)
            ->where('status', 'paid')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('due_date', '>=', $today)
            ->latest('due_date')
            ->first();

        if ($subscription) {
            return $next($request);
        }

        $graceSubscription = Subscription::where(
                'business_id',
                $user->business_id
            )
            ->where('status', 'paid')
            ->whereDate('due_date', '>=', $today->copy()->subDays(5))
            ->whereDate('due_date', '<', $today)
            ->latest('due_date')
            ->first();

        if ($graceSubscription) {
            return $next($request);
        }

        return redirect()
            ->route('login')
            ->with(
                'subscription_message',
                'Your subscription has expired. You have exceeded the 5-day grace period. Please renew your subscription to continue.'
            );
    }
}
