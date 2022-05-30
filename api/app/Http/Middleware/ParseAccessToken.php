<?php
 
namespace App\Http\Middleware;
 
use Closure;
use App\Helpers\JWTHelper;
 
class ParseAccessToken
{
    public function handle($request, Closure $next)
    {
        $jwt = $request->bearerToken();

        if (is_null($jwt) || !JWTHelper::validate($jwt)['valid']) {
            return response()->json([
                'errors' => [
                    'code' => 6,
                    'title' => 'Credenciais inválidas',
                    'description' => 'Não foi possível autenticar o usuário'
                ]
            ], 401);
        }

        $access_token = JWTHelper::parse($jwt);

        $request->merge(['access_token' => $access_token['payload']]);
 
        return $next($request);
    }
}