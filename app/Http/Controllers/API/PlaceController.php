<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
//Model using
use App\Place;

class PlaceController extends BaseController
{
    public function index()

    {

        $place = Place::all();
        return $this->sendResponse($place->toArray(), 'place retrieved successfully.');

    }

    public function store(Request $request){
        $input = $request->all();

        $validator = Validator::make($input , [
            'name' => 'required'
        ]);

        if ($validator -> fails()){
            return $this->sendError('Validation Error', $validator->errrors());
        }
        $input['admin_id'] = auth::user()->id;
        $place = Place::create($input);

        return $this->sendResponse($place->toArray(),'Product created successfully.');
    }

    public function show($id)

    {

        $place = Place::find($id);


        if (is_null($place)) {

            return $this->sendError('Place not found.');

        }


        return $this->sendResponse($place->toArray(), "Place $id retrieved successfully.");

    }

    public function update(Request $request, $id)

    {

        $input = $request->all();
        
        $place = Place::findOrFail($id);
        $validator = Validator::make($input , [
            'name' => 'required'
        ]);

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

        $place->admin_id = $input['admin_id'];
        $place->name = $input['name'];

        $place->save();


        return $this->sendResponse($place->toArray(), 'Product updated successfully.');

    }

    public function destroy($id)
    {
        $place = Place::findOrFail($id);
        $place->delete();
        return $this->sendResponse($place->toArray(), 'Product deleted successfully.');

    }
}
