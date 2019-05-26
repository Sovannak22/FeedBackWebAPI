<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use User;
use Auth;

class AdminHomePageController extends Controller
{
    public function homePageData(){


        $place_id = Auth::user()->place->id;
        $user_count = count(User::all());
        $news_count = count(DB::table('news')
            ->where('place_id',$place_id)
        );
        
        $admin_home_data->user_count = $user_count;
        $admin_home_data->new_count = $news_count;

        return $this->sendResponse($news->toArray(), 'Products retrieved successfully.');

    }
}
