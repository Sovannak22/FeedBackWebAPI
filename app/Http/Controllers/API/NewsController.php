<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\News;
use Validator;
use Image;
use Auth;
use Place;

class NewsController extends BaseController
{

    public function sendResponse($result, $message)
    {

    	$response = $result;


        return response()->json($response, 200);

    }
    public function index(){

        if (Auth::user()->user_role_id == 1){
            $place_id = Auth::user()->place->id;
            $newsList = DB::table("news")->where("place_id",$place_id)->orderBy('created_at','DESC')->get();
            foreach ($newsList as $news){
                $news_id = $news->id;
                $user = News::find($news_id)->place->user;
                $user_name = $user->name;
                // dd($user_name);
                $news->username = $user_name;
                $news->profile_img = $user->profile_img;
                // dd($news);
            }
            return $this->sendResponse($newsList, 'Products retrieved successfully.');
        }
        $news = News::all()->orderBy('created_at','DESC');
        return $this->sendResponse($news->toArray(), 'Products retrieved successfully.');
    }

    public function newsById($id){
        $newsList = DB::table("news")->where("place_id",$id)->orderBy('created_at','DESC')->get();
        foreach ($newsList as $news){
            $news_id = $news->id;
            $user = News::find($news_id)->place->user;
            $user_name = $user->name;
            // dd($user_name);
            $news->username = $user_name;
            $news->profile_img = $user->profile_img;
            // dd($news);
        }
    
        return $this->sendResponse($newsList->toArray(), 'Products retrieved successfully.');
    }

    public function store(Request $request){
        if (Auth::user()->user_role_id != 1){
            return $this->sentError("Invalid user role");
        }
        $input = $request->all();
        $image_url = "";
        $validator = Validator::make($input, [
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
            Image::make($image)->resize(500,350)->save(public_path('/storage/news_image/'.$imageName));
            $image_url = ('storage/news_image/'.$imageName);

        }
        else{
            $image_url = ('storage/news_image/default_news.jpg');
        }
        $input['place_id'] = Auth::user()->place->id;
        $dataToInput = array('place_id'=>$input['place_id'],'title'=>$input['title'],'description'=>$input['description'],'image_url'=>$image_url);
        $news = News::create($dataToInput);

        return $this->sendResponse($news->toArray(), 'News created successfully.');
    }

    public function show($id){
        $news = News::find($id);

        if (is_null($news)){
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse($news->toArray(), 'Feedback found');
    }

    public function update(Request $request, $id){


        $input = $request->all();
        
        $news = News::findOrFail($id);

        $current_user_id = Auth::user()->id;
        $news = News::findOrFail($id);
        $admin_id = $news->place->user->id;
        if ($current_user_id != $admin_id){
            return $this->sendError("Can not update this post");
        }
        $validator = Validator::make($input , [

            // 'feedback_type_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

        if ($request->hasFile('image')){

            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imageName = $imageName.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(500,350)->save(public_path('/storage/news_image/'.$imageName));
            $image_url = ('storage/news_image/'.$imageName);
            $news->image_url = $image_url;

        }
        $news->title = $input['title'];
        $news->description = $input['description'];

        $news->save();


        return $this->sendResponse($news->toArray(), "news $id updated successfully.");

    }

    public function destroy($id){
        $current_user_id = Auth::user()->id;
        $news = News::findOrFail($id);
        $admin_id = $news->place->user->id;
        if ($current_user_id == $admin_id){
            $news->delete();
            return $this->sendResponse($news->toArray(), 'Product deleted successfully.');
        }
        return $this->sendError("Can not delete this post");
        

    }
}
