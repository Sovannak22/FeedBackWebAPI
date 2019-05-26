<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    

    protected $fillable = [
        'feedback_id', 'description', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function feedback(){
        return $this->belongTo('App\Feedback');
    }
}
