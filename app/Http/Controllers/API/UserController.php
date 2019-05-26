<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use User;

class UserController extends BaseController
{
    public function sendResponse($result, $message)
    {

    	$response = $result;


        return response()->json($response, 200);

    }

    public function index(){
        $users = User::all();

        return $this->sendResponse($users->toArray(), 'Products retrieved successfully.');
    }

    //
    public function getCurrentUserInfo(){
        $user = Auth::user();
        // dd($user);
        if (is_null($user)){
            return $this->sendError('Product not found.');
        }
        return $this->sendResponse($user->toArray(), 'News created successfully.');
    }
}
