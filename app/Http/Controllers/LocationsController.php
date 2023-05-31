<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationsController extends Controller
{

public function index()
{
    $locations = Location::all();
    foreach($locations as $location){
        $location->email = json_decode($location->email);
    }
    return response()->json([
        'locations'=>$locations,
    ],200);
}

public function getOne($id)
{
    $location = Location::find($id);
    if($location)
    {
        return response()->json([
            'location'=>$location
        ],200);
    }
    else
    {
        return response()->json([
            'message'=>'Location not found!'
        ],404);
    }
}

public function update(Request $request, $id){
    $validator = Validator::make($request->all(), [
        'title'=>'required|max:191',
        'director'=>'required|max:191',
        'tel'=>'required|max:191',
        'email'=>'required|max:191',
        'scope'=>'required|max:191',
        'categories'=>'required|max:191',
    ]);

    if($validator->fails())
    {
        return response()->json([
            'errors'=>$validator->getMessageBag(),
        ],422);
    }
    else
    {
        $location = Location::find($id);
        if($location)
        {
            $location->title = $request->input('title');
            $location->po = $request->input('po');
            $location->address = $request->input('address');
            $location->director = $request->input('director');
            $location->tel = $request->input('tel');
            $location->email = $request->input('email');
            $location->scope = $request->input('scope');
            $location->categories = $request->input('categories');
            $location->save();
            return response()->json([
                'message'=>'Location updated successfully',
            ],200);
        }
        else
        {
            return response()->json([
                'message'=>'Location not found!'
            ],404);
        }
    }
}

    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'title'=>'required|max:191',
            'director'=>'required|max:191',
            'tel'=>'required|max:191',
            'email'=>'required|max:191',
            'scope'=>'required|max:191',
            'categories'=>'required|max:191',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->getMessageBag(),
            ],400);
        }
        else
        {
            $location = new Location;
            $location->title = $request->input('title');
            $location->po = $request->input('po');
            $location->address = $request->input('address');
            $location->director = $request->input('director');
            $location->tel = $request->input('tel');
            $location->email = json_encode($request->input('email'));
            $location->scope = $request->input('scope');
            $location->categories = $request->input('categories');
            $location->save();
            return response()->json([
                'message'=>'Location added successfully',
            ],200);
        }
    }

    public function destroy($id)
    {
        $location = Location::find($id);
        if($location)
        {
            $location->delete();
            return response()->json([
                'message'=>'Location deleted successfully',
            ],200);
        }
        else
        {
            return response()->json([
                'message'=>'Location not found !',
            ],404);
        }

    }
}
