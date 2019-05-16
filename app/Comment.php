<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    

    public function feedback(){

        return $this->belongsTo('app\Feedback');

    }
}
