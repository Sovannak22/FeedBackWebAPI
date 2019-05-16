<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    //
    protected $fillable = [
        'admin_id','name'
    ];

    public function feedBacks(){
        return $this->hasMany('app\FeedBack');
    }

    public function user(){
        return $this->belongsTo('App\User','admin_id');
    }

    public function news(){
        return $this->hasMany('app\News');
    }
}
