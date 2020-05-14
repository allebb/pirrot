<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Session;

class AuthenticatePirrotAdmin
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (env('APP_ENV') == 'local') {
            // Baypass auth in development mode.
            return $next($request);
        }

        $hash = trim(file_get_contents(storage_path() . '/../../storage/password.vault'));

        if ($request->getUser() == 'admin' && password_verify($request->getPassword(), $hash)) {
            return $next($request);
        }

        $headers = array('WWW-Authenticate' => 'Basic');
        return response('Unauthorized', 401, $headers);

    }
}
