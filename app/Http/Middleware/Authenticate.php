<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Authenticate
{
    /**
     * Redirect user if they are not authenticated.
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse|BinaryFileResponse|StreamedResponse
    {
        if (!is_null(request()->user())) {
            return $next($request);
        } else {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }
            return redirect('login');
        }
    }
}
