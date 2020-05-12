<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Session;

class AuthenticatePirrotAdmin
{

    /**
     * Create a new middleware instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // @todo Will update this to read the password from an encypted file instead of the .env file.
        if ($request->getUser() != 'admin' && $request->getPassword() != env('ADMIN_PASSWORD')) {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('Unauthorized', 401, $headers);
        }

        return $next($request);
    }
}
