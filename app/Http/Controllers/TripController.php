<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Trip;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class TripController extends Controller
{
    use GeneralTrait;

    public function CreateTrip(Request $request)
    {
        $request->validate([
            's_location' => 'required',
            'e_location' => 'required',
            's_lat' => 'required',
            's_long' => 'required',
            'e_lat' => 'required',
            'e_long' => 'required',
            'distance' => 'required',
            'duration' => 'required'
        ]);
        /*
        here check if trips is dummy
        */
        $trip = new Trip();
        if(isset(auth()->user()->customer->id))
            $trip->customer_id = auth()->user()->customer->id;
        else if(isset(auth()->user()->admin->id))
            $trip->customer_id = auth()->user()->admin->id;
        $trip->s_location = $request->s_location;
        $trip->e_location = $request->e_location;
        $trip->s_lat = $request->s_lat;
        $trip->s_long = $request->s_long;
        $trip->e_lat= $request->e_lat;
        $trip->e_long = $request->e_long;
        $trip->distance = $request->distance;
        $trip->duration = $request->duration;
        $trip->save();

        $categories = Category::all();

        foreach ($categories as $category) {
            $category->price = $category->cost *$trip->distance;
        }
        $categories->put('trip_id',$trip->id);

        return $this->returnData('all categories with cost',$categories);

    }
}
