<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FeedBack;
use Validator;

class FeedbackController extends BaseController
{
    //Display list of all feedback
    public function index(){

        $feed_backs = FeedBack::all();
        return $this->sendResponse($feed_backs->toArray(), 'Products retrieved successfully.');
    }

    public function store(Request $request){

        $input = $request->all();
        // dd($input);
        $validator = Validator::make($input, [
            // 'user_id' => 'required',
            'place_id' => 'required',
            'feedback_type_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if ($request->hasFile('image')){

            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imageName = $imageName.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(1000,750)->save(public_path('/storage/news_image/'.$imageName));
            $image_url = ('storage/news_image/'.$imageName);

        }
        else{
            $image_url = ('storage/news_image/default_news.jpg');
        }
        $input['user_id'] = Auth::user()->id;
        $dataToInput = array('place_id'=>$input['place_id'],'user_id'=>$input['user_id'],'feedback_type_id'=>$input['feedback_type_id'],'title'=>$input['title'],
        'description'=>$input['description'],'img'=>$image_url);
        dd($dataToInput);
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
