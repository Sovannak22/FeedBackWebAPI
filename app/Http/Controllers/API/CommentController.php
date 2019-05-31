<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Comment;
use Validator;
use Auth;


class CommentController extends BaseController
{
    //
    public function sendResponse($result, $message)
    {

    	$response = $result;


        return response()->json($response, 200);

    }

    public function index($id){
        $comments = DB::table("comments")->where("feedback_id",$id)->get();
        foreach ($comments as $comment){
            $user = DB::table("users")->where("id",$comment->user_id)->orderBy('created_at','DESC')->get();
            $comment->username = $user[0]->name;
        }
        // dd($comments);
        return $this->sendResponse($comments, 'Products retrieved successfully.');
    }

    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'feedback_id' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input['user_id'] = Auth::user()->id;
        $comment = Comment::create($input);

        return $this->sendResponse($comment->toArray(), 'Comment created successfully.');
    }

    public function destroy($id){
        $comment = Comment::find($id);
        $comment_userId = $comment->user->id;
        $current_user_id = Auth::user()->id;
        if ($current_user_id == $comment_userId){
            $comment->delete();
            return $this->sendResponse($comment->toArray(), 'Product deleted successfully.');
        }
        return $this->sendError("Can not delete this post");
    }
}
