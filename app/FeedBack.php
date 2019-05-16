<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    //

    protected $fillable = [
        'user_id', 'place_id', 'feedback_type_id','title','description','img'
    ];

    public function user(){

        return $this->belongsTo('app\User');
    }

    public function place(){
        return $this->belongsTo('app\Place');
    }

    public function feedbackType(){
        return $this->belongsTo('app\FeedbackType');
    }

    public function comments(){
        return $this->hasMany('app\Comment');
    }
}
