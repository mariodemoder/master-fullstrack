<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;

class PruebasController extends Controller
{
    public function index(){
        $titulo = 'Animales';
        $animales = ['Perro', 'Gato', 'Tigre'];
        return view('pruebas.index', array(
            'titulo' => $titulo,
            'animales' => $animales
        ));
    }

    public function testOrm(){
        /*
        //listamos los post
        $posts = Post::all();
        foreach ($posts as $post) {
            echo "<h1>$post->title</h1>";
            //datos relacionados
            echo "<span style='color:blue;'>{$post->user->name} - {$post->category->name}</span>";
            echo "<p>$post->content</p>";
        }
        */
        // listamos categorias y post
        $categories = Category::all();
        foreach ($categories as $category) {
            echo "<h1>$category->name</h1>";

            foreach ($category->posts as $post) {
                echo "<h3>$post->title</h3>";
                //datos relacionados
                echo "<span style='color:blue;'>{$post->user->name} - {$post->category->name}</span>";
                echo "<p>$post->content</p>";
            }
            echo "<hr>";
        }
        die();
    }
}
