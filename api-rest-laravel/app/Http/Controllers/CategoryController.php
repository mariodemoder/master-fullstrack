<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;

class CategoryController extends Controller
{
    public function __construct(){
        //cargar el middleware
        $this->middleware('api.auth',['except'=>['index', 'show']]);
    }

    public function index(){
        /*
        $categories = Category::all();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'categories' => $categories
        ]);
        */
    }

    public function show($id){/*
        $category = Category::find($id);
        if(is_object($category)){
            $data= [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];
        }else{
            $data= [
                'code' => 404,
                'status' => 'error',
                'message' => 'La categoria no existe'
            ];
        }
         return response()->json($data, $data['code']);*/
    }
    // ---------------------------------------------------------------------------------------------------------------------------------
    //pasara por el middleware
        public function store(Request $request){

        // recoger datos que vienen por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        // validar datos
        if(!empty($params_array)){
            $validate = \Validator::make($params_array,[
                'name' => 'required'
            ]);
        // guardar la categoria
                if($validate->fails()){
                    $data= [
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'No se ha guardado la categoria'
                    ];
                } else {
                    $category = new Category();
                    $category->name = $params_array['name'];
                    $category->save();
                    $data= [
                        'code' => 200,
                        'status' => 'success',
                        'category' => $category
                    ];
                }
        }else{
                $data= [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha enviado la categoria'
                ];
        }
        //devolver el resultado
        return response()->json($data, $data['code']); 

        }
// ---------------------------------------------------------------------------------------------------------------------------------

        public function update($id, Request $request){
            // recoger datos que vienen por post
            $json = $request->input('json', null);
            $params_array = json_decode($json, true);
            // validar datos
            if(!empty($params_array)){
                $validate = \Validator::make($params_array,[
                    'name' => 'required'
                ]);
            // quitar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            // actualizar registro
            $category = Category::where('id', $id)->update($params_array);

             $data= [
                        'code' => 200,
                        'status' => 'se actualizo la categoria',
                        'category' => $params_array
                    ];
        }else{
            $data= [
                'code' => 400,
                'status' => 'error',
                'message' => 'No se ha enviado la categoria'
            ];
        }
            //devolver datos
            return response()->json($data, $data['code']); 
        }

        
}
/*
|        | GET|HEAD  | api/category                 | category.index   | App\Http\Controllers\CategoryController@index   | web                                       |
|        | POST      | api/category                 | category.store   | App\Http\Controllers\CategoryController@store   | web                                       |
|        | GET|HEAD  | api/category/create          | category.create  | App\Http\Controllers\CategoryController@create  | web                                       |
|        | DELETE    | api/category/{category}      | category.destroy | App\Http\Controllers\CategoryController@destroy | web                                       |
|        | PUT|PATCH | api/category/{category}      | category.update  | App\Http\Controllers\CategoryController@update  | web                                       |
|        | GET|HEAD  | api/category/{category}      | category.show    | App\Http\Controllers\CategoryController@show    | web                                       |
|        | GET|HEAD  | api/category/{category}/edit | category.edit    | App\Http\Controllers\CategoryController@edit    | web                                       |
*/