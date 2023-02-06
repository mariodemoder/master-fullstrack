<?php
//udemy
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;


class UserController extends Controller
{
    public function pruebas(Request $request){
        return "Accion de pruebas de USER-CONTROLLER";
    }
// --------------------------------------------------------------------------------------------------------
    public function register(Request $request){
        /* pruebas con postman .... como meter inputs y retornar datos
        $name = $request->input('name');
        $surname = $request->input('surname');
        return "Accion registro de usuarios: $name $surname";
        */
        // -------------------------------------------------------------------
        // 1 - Recoger datos del usuario por post
        $json = $request->input('json', null);

        //nos devuelve un objeto json
        $params = json_decode($json);
        //var_dump($params);        die();
        //nos devuelve un array
        $params_array = json_decode($json, true);
        //var_dump($params_array);        die();

        // 2 - si no esta vacio
        if(!empty($params) && !empty($params_array)){
        // -------------------------------------------------------------------
        // 3 - Limpiar datos
        $params_array = array_map('trim', $params_array);
        // -------------------------------------------------------------------
        // 4 - Validar datos
        $validate = \Validator::make($params_array, [
            'name' => 'required|alpha',
            'surname' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            $data = array(
                'status' =>'error',
                'code' => 404,
                'message' => 'Usuario no creado',
                'errors' => $validate->errors()
            );
        }else{

            //validacion pasada correctamente
            // -------------------------------------------------------------------
            // cifrar contraseña
            $pwd = hash('sha256', $params->password);
            // -------------------------------------------------------------------
            // comprobar si usuario existe (se ingreso unique en email)
            // -------------------------------------------------------------------
            // crear usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->role = 'ROLE_USER';
                $user->password = $pwd;
            // -------------------------------------------------------------------
            //guardar el usuario
            $user->save();
            // -------------------------------------------------------------------
            $data = array(
                'status' =>'success',
                'code' => 200,
                'message' => 'Usuario creado correctamente',
                'USER' => $user
            );
        }
    }else{
        $data = array(
            'status' =>'error',
            'code' => 404,
            'message' => 'Los datos enviados no son correctos'
        );
    }
        //devolver con json
        return response()->json($data, $data['code']);
    }
// --------------------------------------------------------------------------------------------------------
    public function login(Request $request){
        $jwtAuth = new \JwtAuth();

        //Recibir datos por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        // -------------------------------------------------------------------
        //Validar los datos
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            $signup = array(
                'status' =>'error',
                'code' => 404,
                'message' => 'Usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        }else{
            // -------------------------------------------------------------------
            //Cifrar la password
            $pwd = hash('sha256', $params->password);
            // -------------------------------------------------------------------
            //Devolver Token o datos
            $signup = $jwtAuth->signup($params->email, $pwd);
            // -------------------------------------------------------------------
            //si ya genero el token devuelve datos
            if (!empty($params->gettoken)){
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }
       //var_dump($pwd); die();
        return response()->json($signup, 200);
    }
// --------------------------------------------------------------------------------------------------------
    //actualizar datos del usuario
    public function update(Request $request){

        //chekear la validez del token
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $chekToken = $jwtAuth->checkToken($token);

        //recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
// var_dump($params_array); die();

        if($chekToken && !empty($params_array)){
            //echo "<h1>Login Correcto</h1>";
            //ACTUALIZAR USUARIO -------------------------------

            //sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);
            //validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,'.$user->sub
            ]);
            //quiar los campos que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            //actualizar en la DB
            $user_update = User::where('id', $user->sub)->update($params_array);

            //devolver array con resultados Y MOSTRARLOS
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array
            );

        }else{
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado.'
            );
        }
        return response()->json($data, $data['code']);

    }
// --------------------------------------------------------------------------------------------------------
public function upload(Request $request){
    // Recoger datos de la peticion
    $image = $request->file('file0');
    // Validar la imagen
    $validate = \Validator::make($request->all(), [
        'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
    ]);

    // Guardar la imagen
    if(!$image || $validate->fails()){
        $data = array(
            'code' => 400,
            'status' => 'error',
            'message' => 'Error al querer subir la imagen'
        );
    }
    else
    {
       // echo $image->getClientOriginalName();
        $image_name = time().$image->getClientOriginalName();
        \Storage::disk('users')->put($image_name, \File::get($image));

        $data = array(
            'code'=> 200,
            'status' => 'success',
            'image' => $image_name
        );
    }
    // Devolver resultado
    return response()->json($data, $data['code']);
    }
//--------------------------------------------------------------------------------------------------------
    public function getImage($filename){


        $isset = \Storage::disk('users')->exists($filename);

        //echo 'getImage'; die();
        if ($isset){
            $file = \Storage::disk('users')->get($filename);
            return new Response($file, 200);
        }
        else{
            $data = array(
                'code'=> 404,
                'status' => 'error',
                'message' => 'La imagen no existe'
            );
            return response()->json($data, $data['code']);
        }
//storage\app\users\1671036047error1.png
    }
//--------------------------------------------------------------------------------------------------------
    public function detail($id){
        $user = User::find($id);
        if (is_object($user)){
            $data = array(
                'code'=> 200,
                'status' => 'success',
                'user' => $user
            );
        }else{
            $data = array(
                'code'=> 404,
                'status' => 'error',
                'message' => 'El usuario  no existe'
            );
        }
        return response()->json($data, $data['code']);
    }
}
