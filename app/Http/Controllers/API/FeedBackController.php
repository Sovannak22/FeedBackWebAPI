<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\FeedBack;
use Validator;
use Auth;
use Image;
use Comment;


class FeedBackController extends BaseController
{
    public function sendResponse($result, $message)
    {

    	$response = $result;


        return response()->json($response, 200);

    }
    //Display list of all feedback
    public function index(){

        $feed_backs = FeedBack::all();
        return $this->sendResponse($feed_backs->toArray(), 'Products retrieved successfully.');
    }

    public function feedbackPublicById($id){
        $feedbacks = DB::table("feed_backs")->where("place_id",$id)
                    ->where('feedback_type_id',1)
                    ->orderBy('created_at','DESC')->get();
        foreach ($feedbacks as $feedback){
            $id=$feedback->id;
            $comments = Feedback::find($id)->comments;
            $user = DB::table("users")->where("id",$feedback->user_id)->orderBy('created_at','DESC')->get();
            $feedback->username = $user[0]->name;
            // dd(count($comments));
            $feedback->comments_count = count($comments);
        }
        return $this->sendResponse($feedbacks, 'Products retrieved successfully.');
    }

    public function feedbackPrivateById(){
        $id = $place_id = Auth::user()->place->id;
        $feedbacks = DB::table("feed_backs")->where("place_id",$id)
                    ->where('feedback_type_id',2)
                    ->orderBy('created_at','DESC')
                    ->get();

        foreach ($feedbacks as $feedback){
            $id=$feedback->id;
            $comments = Feedback::find($id)->comments;
            $user = DB::table("users")->where("id",$feedback->user_id)->orderBy('created_at','DESC')->get();
            $feedback->username = $user[0]->name;
            // dd(count($comments));
            $feedback->comments_count = count($comments);
        }

        return $this->sendResponse($feedbacks, 'Products retrieved successfully.');
    }

    public function store(Request $request){

        $input = $request->all();
        $validator = Validator::make($input, [
            'place_id' => 'required',
            'feedback_type_id' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($request->hasFile('img')){

            $image = $request->file('img');
            $imageName = $image->getClientOriginalName();
            $imageName = $imageName.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(2000,3000)->save(public_path('/storage/feedback_image/'.$imageName));
            $image_url = ('storage/feedback_image/'.$imageName);

        }
        else{
            $image_url=null;
        }
        $input['user_id'] = Auth::user()->id;
        $dataToInput = array('place_id'=>$input['place_id'],'user_id'=>$input['user_id'],'feedback_type_id'=>$input['feedback_type_id'],
        'description'=>$input['description'],'img'=>$image_url);
        // dd($dataToInput);
        $feedback = FeedBack::create($dataToInput);
        return $this->sendResponse($feedback->toArray(), 'Product created successfully.');
    }

    public function show($id){
        $feedback = FeedBack::find($id);

        if (is_null($feedback)){
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse($feedback->toArray(), 'Feedback found');
    }

    public function update(Request $request, $id)

    {

        $input = $request->all();
        
        $feedback = FeedBack::findOrFail($id);
        $validator = Validator::make($input , [

            // 'feedback_type_id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

        $feedback->title = $input['title'];
        $feedback->description = $input['description'];

        $feedback->save();


        return $this->sendResponse($feedback->toArray(), "feedback $id updated successfully.");

    }

    public function destroy($id)
    {
        $feedback = FeedBack::findOrFail($id);
        $feedback->delete();
        return $this->sendResponse($feedback->toArray(), 'Product deleted successfully.');

    }


}
