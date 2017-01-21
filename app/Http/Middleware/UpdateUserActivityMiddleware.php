<?php
namespace TorNas\Http\Middleware;

use Closure;
use Carbon\Carbon;

class UpdateUserActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $currentTime = Carbon::now();

        if ($user && $user->last_activity->diffInSeconds($currentTime) > 60) {
            $user->last_activity = $currentTime;
            $user->save();
        }

        return $next($request);
    }
}
