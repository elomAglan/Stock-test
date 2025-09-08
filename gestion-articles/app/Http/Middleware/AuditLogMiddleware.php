<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AuditLog;

class AuditLogMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = $request->user();
        if (!$user) return $response;

        // Ignore login et register
        if (in_array($request->path(), ['login', 'register'])) {
            return $response;
        }

        // Crée le log
        AuditLog::create([
            'user_id' => $user->id,
            'action' => $request->method() . ' ' . $request->path(),
            'details' => json_encode($request->except(['password', 'password_confirmation'])),
            // 'created_at' est automatique
        ]);

        return $response;
    }
}
