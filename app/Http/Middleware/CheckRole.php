<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Проверяем аутентификацию
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Если ролей не передано, проверяем только аутентификацию
        if (empty($roles)) {
            return $next($request);
        }

        // Проверяем, есть ли у пользователя одна из требуемых ролей
        foreach ($roles as $role) {
            if ($this->hasRole($user, $role)) {
                return $next($request);
            }
        }

        // Если доступ запрещен
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        abort(403, 'Доступ запрещен');
    }

    /**
     * Проверить роль пользователя
     */
    private function hasRole($user, $role): bool
    {
        // Проверяем методы модели User
        return match($role) {
            'admin' => $user->isAdmin(),
            'manager' => $user->isManager(),
            'user' => true, // Любой аутентифицированный пользователь
            default => false,
        };
    }
}
