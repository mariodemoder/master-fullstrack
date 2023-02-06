<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['title', 'content','category_id', 'image'];

    //relacion de uno a muchos inversa (muchos Post a un usuario)
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    //relacion de uno a muchos inversa (muchos Post a una categoria)
    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

}
