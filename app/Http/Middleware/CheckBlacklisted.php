<?php

namespace App\Http\Middleware;

use App\Models\GroupMember;
use Closure;
use Illuminate\Http\Request;

class CheckBlacklisted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Check if the user account itself is blocked
        if ($user->status === 'blocked') {
            return response()->view('student.blacklisted', [
                'reason' => 'blocked',
                'until' => null,
            ]);
        }

        // Check if blacklisted from a group due to inactivity
        $blacklist = GroupMember::where('user_id', $user->id)
            ->where('status', 'blacklisted')
            ->where('blacklisted_until', '>', now())
            ->latest('blacklisted_until')
            ->first();

        if ($blacklist) {
            return response()->view('student.blacklisted', [
                'reason' => 'inactivity',
                'until' => \Carbon\Carbon::parse($blacklist->blacklisted_until),
            ]);
        }

        return $next($request);
    }
}