<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Post;
use App\Helpers\JwtAuth;

class PostController extends Controller
{
   /* public function pruebas(Request $request){
        return "Accion de pruebas de POST-CONTROLLER";
    }
    */

    public function __construct(){
        //cargar el middleware
        $this->middleware('api.auth',['except'=>[
            'index',
             'show',
            'getImage',
            'getPostsByCategory',
            'getPostsByUser'
            ]]);
    }
// -------------------------------------------------------------------------------------------------
    public function index(){
        $posts = Post::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }
// -------------------------------------------------------------------------------------------------
    public function show($id){
        $post = Post::find($id)->load('category');

        if (is_object($post)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'posts' => $post
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La entrada no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }
// -------------------------------------------------------------------------------------------------
    public function store(Request $request){

        // recoger datos por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if (!empty($params_array)){
        // conseguir usuarios identificados
            $user = $this->getIdentity($request);
        // validar datos
            $validate = \Validator::make($params_array,[
                'title'=>'required',
                'content'=>'required',
                'category_id'=>'required',
                'image'=>'required'
            ]);
            //para pruebas
            //{"title":"VACAS2","content":"CONTENIDO DE VACAS","category_id":"1","image":"ima2.png"}
            if($validate->fails()){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardad, faltan datos'
                ];
            }else{
                // guardar datos
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;

                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Datos guardados correctamente',
                    'post' => $post
                ];
            }
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No se ha guardad, faltan datos'
            ];
        }
        // devolver respuesta
        return response()->json($data, $data['code']);
    }
// -------------------------------------------------------------------------------------------------
    public function update($id, Request $request){
        // recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        $user = $this->getIdentity($request);

        //data por defecto
        $data = array(
            'code' => 200,
            'status' => 'error',
            'message' => 'Datos incorrectos'
        );

        if(!empty($params_array)){
        // validar datos
            $validate = \Validator::make($params_array,[
                'title'=>'required',
                'content'=>'required',
                'category_id'=>'required'
                ]);
            if($validate->fails()){
                $data['errors'] = $validate->errors();
            }
            else {
                // eliminar datos que no quiero modificar
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                $post =  Post::find($id); //Buscar registro
                // actualizar registro solo ese id y si es usuario creador
                $post_update = Post::where('id', $id)
                                ->where('user_id',$user->sub)
                                ->update($params_array);
                // devolver respuesta
                if($post_update){
                    $data = [
                    'code'          => 200,
                    'status'        => 'success',
                    'previous_post' => $post,
                    'post_changed'  => $params_array
                    ];
                }else{
                    $data = [
                    'code'    => 404,
                    'status'  => 'error',
                    'message' => 'El post que intentas actualizar no existe o no tienes permiso'
                    ];
                    }
                }
            }else{
                $data = array(
                    'code' => 200,
                    'status' => 'error',
                    'message' => 'Datos no ingresados'
                );
            }

            return response()->json($data, $data['code']);
    }
// -------------------------------------------------------------------------------------------------
    public function destroy($id, Request $request){
       // conseguir usuarios identificados
        $user = $this->getIdentity($request);
        // conseguir el registro con id y solo del usuario identificado
        $post = Post::where('id', $id)->where('user_id', $user->sub)->first();

        //echo $user->sub; die();
        if(!empty($post)){
            // borrarlo
            $post->delete();
            $data = array(
                'code' => 200,
                'status' => 'success',
                'post' => $post
            );
        }
        else{
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El post no existe o no tiene permiso para eliminar'
            );
        }
        // devolver algo
        return response()->json($data, $data['code']);
        }
// -------------------------------------------------------------------------------------------------
        private function getIdentity($request){
            // conseguir usuarios identificados
            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization', null);
            $user = $jwtAuth->checkToken($token, true);
            return $user;
        }
// -------------------------------------------------------------------------------------------------
        public function upload(Request $request){
            // recoger la imagen de la peticion
            $image = $request->file('file0');

            // validar imagen
            $validate = \Validator::make($request->all(), [
                'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
            ]);
            // guardar imagen
            if(!$image || $validate->fails()){
                $data = array(
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Error al subir imagen'
                );
            }else{
                $image_name = time().$image->getClientOriginalName();
                \Storage::disk('images')->put($image_name, \File::get($image));
                $data = array(
                    'code'=> 200,
                    'status' => 'success',
                    'image' => $image_name
                );
            }
            // Devolver resultado
            return response()->json($data, $data['code']);
        }
// -------------------------------------------------------------------------------------------------
        public function getImage($filename){
            // comprobar si existe el fichero
            $isset = \Storage::disk('users')->exists($filename);
            if($isset){
                // conseguir la imagen
                $file = \Storage::disk('users')->get($filename);
                // devolver la imagen
                return new Response($file, 200);
            }else{
                $data = [
                    'code' => 404,
                    'status' => 'error', 
                    'message' => 'La imagen no exite'
                    ];
            }
            return response()->json($data, $data['code']);
        }
    // -------------------------------------------------------------------------------------------------
        public function getPostsByCategory($id){
            $post = Post::where('category_id', $id)->get();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => $post
                ];

                return response()->json($data, $data['code']);
        }
        public function getPostsByUser($id){
            $post = Post::where('user_id', $id)->get();

            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => $post
                ];

                return response()->json($data, $data['code']);
        }
    // -------------------------------------------------------------------------------------------------
}
