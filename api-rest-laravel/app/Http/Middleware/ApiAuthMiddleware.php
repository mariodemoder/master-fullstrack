<?php
//udemy
namespace App\Http\Middleware;

use Closure;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //chekear la validez del token
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $chekToken = $jwtAuth->checkToken($token);
//echo $chekToken; die();

        if($chekToken){
            return $next($request);
        }
        else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado.'
            );
            return response()->json($data, $data['code']);
        }
    }
}
