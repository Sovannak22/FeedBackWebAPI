<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use User;

class TestController extends Controller
{
    //

    public function getPlaceByUser($id){
        echo User::find($id)->place->id;
    }
}
