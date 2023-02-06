<?php
//udemy
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

    public $key;

    public function __construct() {
        $this->key = 'clave_super_power_secretisima';
    }

    public function signup($email, $password, $gettoken = null){
    // buscar si existe el usuario con sus credenciales --------------------------------------
    $user = User::where([
        'email' => $email,
        'password' => $password
    ])->first();

    // comprobar si son correctos ------------------------------------------------------------
    $signup = false;
    if (is_object($user)){
        $signup = true;
    }
    // generar el token con los datos del usuario identificado ------------------------------
    if($signup){

        $token = array(
            'sub'   => $user->id,
            'email'   => $user->email,
            'name'   => $user->name,
            'surname'   => $user->surname,
            'iat'       => time(),
            'exp'       => time()+(7* 24 * 60* 60 )
        );

        $jwt =      JWT::encode($token , $this->key,    'HS256');
        $decoded =  JWT::decode($jwt,   $this->key,     ['HS256']);

        // devolver los datos decodificados o el token en funcion del parametro gettoken true ------
        if(is_null($gettoken)){
            $data = $jwt;
        }
        else{
            $data = $decoded;
        }
    }
    //si no pudo loguearse entonces muestra un error
    else {
        $data = array(
            'status' => 'error',
            'message' => 'Login Incorrecto'
        );
    }

    return $data;
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $jwt = str_replace('"','', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DominException $e){
            $auth = false;
        }
        //si existe decoded y es un objeto y si existe decoded sub si tenemos el id del usuario
        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }
        else {
            $auth = false;
        }

        // comprobar si llega get identity
        if ($getIdentity){
            return $decoded;
        }

        return $auth;
    }

}
