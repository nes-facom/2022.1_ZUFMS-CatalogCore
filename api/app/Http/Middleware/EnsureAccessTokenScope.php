<?php
 
namespace App\Http\Middleware;
 
use Closure;
use App\Helpers\ArrayHelper;
 
class EnsureAccessTokenScope
{
    public function handle($request, Closure $next, ...$scopes)
    {
        $access_token = $request->get('access_token');

        if (is_null($access_token)) {
            return response()->json([
                'errors' => [
                    'code' => 6,
                    'title' => 'Credenciais inválidas',
                    'description' => 'Não foi possível autenticar o usuário'
                ]
            ], 401);
        }

        $access_token_scope_array = explode(' ', $access_token['scope']);

        if (!ArrayHelper::all_in_array($scopes, $access_token_scope_array)) {
            return response()->json([
                'errors' => [
                    'code' => 1,
                    'title' => 'Permissões Insuficientes',
                    'description' => 'Você não possui as permissões necessárias para realizar esta operação',
                ]
            ], 403);
        }
 
        return $next($request);
    }
}