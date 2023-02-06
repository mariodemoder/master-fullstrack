<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', function () {
    return '<h1>HOLA MUNDO</h1>';
});


Route::get('/welcome', function () {
    return view('welcome');
});
/*
route::get('/pruebas/{nombre?}',function($nombre = null){
    $texto = '<h2>texto de esta ruta</h2>';
    $texto .= ' Nombre : '.$nombre;
    //return $texto;
    return view('pruebas',array(
        'texto' => $texto
    ))
})
*/
route::get('/animales','PruebasController@index');
route::get('/test-orm','PruebasController@testOrm');
//RUTAS DE API udemy
/*
    metodos http que usa api rest full
        get conseguir datos
        post guardar o hacer logica
        put actualizar
        delete elminar
*/
    // //RUTAS DE PRUEBA
    // route::get('/usuario/pruebas','Usercontroller@pruebas');
    // route::get('/entrada/pruebas','Postcontroller@pruebas');
    // route::get('/categoria/pruebas','Categorycontroller@pruebas');

    //RUTAS DE CONTROLADOR USUARIOS
    Route::post('/api/register','UserController@register');
    Route::post('/api/login','UserController@login');
    Route::post('/api/update','UserController@update');

    //para consultar las rutas metodos actions y middleware en la consola (crl + ñ) usar el comando  php artisan route:list
    Route::put('/api/user/update','UserController@update');

    Route::post('/api/user/upload','UserController@upload')->middleware(ApiAuthMiddleware::class);
    Route::get('/api/user/avatar/{filename}','UserController@getImage');
    Route::get('/api/user/detail/{id}','UserController@detail');

    Route::resource('/api/category', 'CategoryController');
    Route::resource('/api/post', 'PostController');

    Route::post('/api/post/upload','PostController@upload');
    Route::get('/api/post/image/{filename}','PostController@getImage');

    route::get('/api/post/category/{id}','PostController@getPostsByCategory');
    route::get('/api/post/user/{id}','PostController@getPostsByUser');