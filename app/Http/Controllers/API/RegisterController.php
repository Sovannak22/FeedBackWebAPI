<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;

use App\User;

use Illuminate\Support\Facades\Auth;

use Validator;


class RegisterController extends BaseController

{

    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function sendResponse($result, $message)

    {

    	$response = [

            'success' => true,

            'name'    => $result['name'],

            'token' => $result['token'],

            'message' => $message,

        ];


        return response()->json($response, 200);

    }
    public function register(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required',

            'student_id' => 'required',

            'password' => 'required',

            'c_password' => 'required|same:password',
            
            'gender' => 'required'

        ]);


        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }


        $input = $request->all();
        // dd($input);
        $input['user_role_id'] = 1;
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] =  $user->createToken('MyApp')->accessToken;

        $success['name'] =  $user->name;


        return $this->sendResponse($success, 'User register successfully.');

    }

}