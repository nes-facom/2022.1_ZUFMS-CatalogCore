<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\UserRole;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class CheckUserRoles extends BaseMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = $this->auth->parseToken()->authenticate();
        $accessGranted = $this->checkForUserRole($user, $roles);

        if(!$accessGranted)
            return response()->json(['status' => 'Permission Denied'], 403);

        return $next($request);
    }


    private function checkForUserRole($user, $roles){
        $middlewareRoles = explode('|', $roles);

        $userRoles = UserRole::query()
            ->where('user_id', '=', $user->id)->get()->pluck('role_id')
            ->toArray();

        $middlewareRoles = Role::query()
            ->whereIn('name', $middlewareRoles)->get()->pluck('id')
            ->toArray();

        foreach ($userRoles as $userRole){
            if(in_array($userRole, $middlewareRoles)){
                return true;
            }
        }

        return false;
    }

}
