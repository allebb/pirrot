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

        // @todo Will update this to read the password from an encypted file instead of the .env file.

        //app('pirrot.config')->timezone; //will return the timezone from the Pirrot Config file.
        if ($request->getUser() != 'admin' && $request->getPassword() != env('ADMIN_PASSWORD')) {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('Unauthorized', 401, $headers);
        }

        return $next($request);
    }
}
