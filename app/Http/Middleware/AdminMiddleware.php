<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{   public function handle(Request $request, Closure $next)
    {
        $id = Auth::id();
        $user = User::find($id);
        if ($user->role === 'Admin') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
