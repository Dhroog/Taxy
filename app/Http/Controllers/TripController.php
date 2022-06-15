<?php

namespace App\Http\Controllers;

use App\Events\SearchAboutDrivers;
use App\Jobs\SearchAboutDriver;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Trip;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
    use GeneralTrait;

    public function CreateTrip(Request $request): JsonResponse
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
        $user = auth()->user();
        $trip = new Trip();
        if (isset($user->customer->id)) {
            $trip->customer_id = $user->customer->id;
            $trip->customer_name = $user->name;
            $trip->customer_phone = $user->phone;
            $trip->customer_image = $user->image;
        } else
            if (isset($user->admin->id)) {
                $trip->customer_id = $user->admin->id;
                $trip->customer_name = $user->name;
                $trip->customer_phone = $user->phone;
                $trip->customer_image = $user->image;
            }

        $trip->s_location = $request->s_location;
        $trip->e_location = $request->e_location;
        $trip->s_lat = $request->s_lat;
        $trip->s_long = $request->s_long;
        $trip->e_lat = $request->e_lat;
        $trip->e_long = $request->e_long;
        $trip->distance = $request->distance;
        $trip->duration = $request->duration;
        $trip->save();

        $categories = Category::all();

        foreach ($categories as $category) {
            $category->price = $category->cost * $trip->distance;
        }
        $categories->put('trip_id', $trip->id);

        return $this->returnData('all categories with cost', $categories);

    }

    public function ConfirmTrip(Request $request)
    {
        $request->validate([
            'category_id' => 'required|int',
            'trip_id' => 'required|int'
        ]);

        $trip = Trip::find($request->trip_id);
        if (isset($trip)) {
            ///////get all available drivers
            $drivers = Driver::where('available', true)->get();
            foreach ($drivers as $driver) {
                //check if driver Belongs To Circle customer
                if ( $this->BelongsToCircle(100, $trip->s_lat, $trip->s_long, $driver->lat, $driver->long) && $driver->car->category->id == $request->category_id)
                {
                    ///check if driver not reject this trip before
                    $rejections = $driver->rejection;
                    $re = true;
                    if (isset($reject)) {
                        foreach ($rejections as $value) {
                            if ($value->trip_id == $request->trip_id) {
                                $re = false;
                            }
                        }
                    }
                    ////send notification to driver
                    if ($re) {
                        $this->sendnotification($driver->user->fcm_token, 'Trip Available ', 'there new trip can accept it');
                    }
                }
            }
            return $this->returnSuccessMessage('we searching about drivers');
        }
        else return $this->returnError('trip not found');
    }

}
