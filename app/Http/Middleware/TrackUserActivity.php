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
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cacheKey = 'user_activity_' . $user->id;

            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, 'Online', now()->addMinutes(30));
                broadcast(new UserStatusChangedEvent($user, 'Online'))->toOthers();
            } else {
                Cache::put($cacheKey, 'Online', now()->addMinutes(30));
            }
        }
        return $next($request);
    }
}
