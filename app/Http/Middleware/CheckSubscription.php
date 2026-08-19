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

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        if (! $user->business_id) {
            abort(403, 'No business is associated with this account.');
        }

        $today = now();

        $subscription = Subscription::where('business_id', $user->business_id)->orderBy('due_date', 'desc')->first();
        if (! $subscription) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()
                ->route('login')
                ->with(
                    'subscription_message',
                    'No subscription record found. Please contact admin.'
                );
        }

        if ($today->lessThanOrEqualTo($subscription->due_date)) {
            return $next($request);
        }
        $gracePeriodEnd = $subscription->due_date->copy()->addDays(5);
        if ($today->lessThanOrEqualTo($gracePeriodEnd)) {
            return $next($request);
        }

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'subscription_message',
                'Subscription expired. Please contact admin.'
            );
    }
}
