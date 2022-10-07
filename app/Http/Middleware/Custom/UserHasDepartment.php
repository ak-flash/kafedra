<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserHasDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return string
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user() && ! $request->user()?->main_department) {
            return redirect()->route('no-department');
        }

        return $next($request);
    }
}
