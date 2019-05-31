<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use User;
use Image;
use DB;

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

    public function updateProfilePicture(Request $request){

        // dd($request);
        $user = Auth::user();
        $image_url = $user->profile_img;
        $id = $user->id;
        if ($request->hasFile('image')){

            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imageName = $user->name.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(240,240)->save(public_path('/storage/profile_image/'.$imageName));
            $image_url = ('storage/profile_image/'.$imageName);
            $user->profile_img = $image_url;

        }

        DB::table('users')
        ->where('id', $id)
        ->update(['profile_img' => $image_url]);

        return $this->sendResponse($user->toArray(), 'Product created successfully.');
    }
}
