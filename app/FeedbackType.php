<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackType extends Model
{
    //
    public function feedBack(){
        return $this->hasMany('app\FeedbackType');
    }
}
