<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    protected $table = 'news';
    protected $fillable = ['place_id','title','description','image_url'];

    public function place(){
        return $this->belongsTo('app\Place');
    }

    public function newsImgs(){
        return $this->hasMany('app\NewsImg');
    }
}
