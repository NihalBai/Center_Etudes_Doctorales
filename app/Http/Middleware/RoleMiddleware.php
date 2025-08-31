<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class RoleMiddleware
{

    protected $roles;

    /**
     * Create a new middleware instance.
     *
     * @param  string  ...$roles
     * @return void
     */
    public function __construct(...$roles)
    {
        $this->roles = $roles;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {   
    //     if(!empty(Auth::check())){
    //         return $next($request);
    //     }
    //     else{
    //         Auth::logout();
    //         return redirect('login');
    //     }
      
    // }

    // public function handle(Request $request, Closure $next, $role): Response
    // {
    //     // Check if the user is authenticated
    //     if (!Auth::check()) {
    //         // Redirect to login if the user is not authenticated
    //         return redirect('login');
    //     }

    //     // Check if the authenticated user has the required role
    //     if (!$request->user()->hasRole($role)) {
    //         // Redirect to unauthorized page if the user does not have the required role
    //         return redirect('unauthorized');
    //     }

    //     // Proceed with the request if the user is authenticated and has the required role
    //     return $next($request);
    // }
    
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user() || ! $request->user()->hasRole($role)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
