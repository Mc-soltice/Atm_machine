<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Logging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckUserLock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user && $user->loginAttempt) {
            $attempts = $user->loginAttempt->attempts;
            $lockedUntil = $user->loginAttempt->locked_until;

            if ($lockedUntil && Carbon::now()->lessThan($lockedUntil)) 
            {
                return response()->json(['message' => 'Account is locked.'], 423);
            }

            if ($attempts >= 3) {
                $user->loginAttempt->update([
                    'locked_until' => Carbon::now()->addMinutes(60),
                    'attempts' => 0,
                ]);

                Logging::store("Account locked! : {$request['email']}");
                return response()->json(['message' => 'Account is locked.'], 423);
            }
        }

        return $next($request);
    }
}