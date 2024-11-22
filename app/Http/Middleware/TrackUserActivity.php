<?php

namespace App\Http\Middleware;

use App\Events\UserStatusChangedEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cacheKey = 'user_activity_' . $user->id;

            Cache::put($cacheKey, 'Online', now()->addMinutes(30)); 

            broadcast(new UserStatusChangedEvent($user, 'Online'))->toOthers();
        }
        return $next($request);
    }
}
