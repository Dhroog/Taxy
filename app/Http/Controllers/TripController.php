<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Driver;
use App\Models\Position;
use App\Models\Rejectation;
use App\Models\Trip;
use App\Models\User;
use App\Traits\GeneralTrait;
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
            $trip->user_id = $user->id;
            $trip->customer_name = $user->name;
            $trip->customer_phone = $user->phone;
            $trip->customer_image = $user->image;


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

    public function ConfirmTrip(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => 'required|int',
            'trip_id' => 'required|int',
            'radius' => 'int'
        ]);
        $countOfDriverFound = 0;
        $radius = 100;
        if(isset($request->radius))$radius = $request->radius;

        $trip = Trip::find($request->trip_id);
        $category = Category::find($request->category_id);
        if (isset($trip,$category)) {

            //calculate cost trips and save it
            $trip->cost = $category->cost * $trip->distance;
            $trip->confirmed = true;
            $trip->category_id = $request->category_id;
            $trip->save();
            ///////get all available drivers
            $drivers = Driver::where('available', true)->get();
            foreach ($drivers as $driver) {
                //check if driver Belongs To Circle customer and his car belongs to category trip and have money in his balance
                $DriverBelongsToCircleSearch = $this->BelongsToCircle($radius, $trip->s_lat, $trip->s_long, $driver->lat, $driver->long);
                $DriverHaveSameCategory = $driver->car->category->id == $request->category_id;
                $DriverHasMoney = $driver->balance->amount >= ($trip->cost * 10)/100;

                if ( $DriverHasMoney && $DriverHaveSameCategory && $DriverBelongsToCircleSearch )
                {
                    ///check if driver not reject this trip before
                    $rejections = Rejectation::where([
                        ['driver_id','=',$driver->id],
                        ['trip_id','=',$trip->id]
                    ])->get();
                    /*
                    $rejections = $driver->rejection;
                    $re = true;
                    if (isset($reject)) {
                        foreach ($rejections as $value) {
                            if ($value->trip_id == $request->trip_id) {
                                $re = false;
                            }
                        }
                    }
                    */
                    ////send notification to driver
                    if ($rejections->isEmpty()) {
                        $this->sendnotification($driver->user->fcm_token, 'Trip Available ', 'there a new trip you can accept it');
                        $countOfDriverFound++;
                    }
                }
            }
            if($countOfDriverFound != 0) return $this->returnSuccessMessage('we searching about drivers');
            else return $this->returnSuccessMessage("didn't found any driver now ");


        }
        else return $this->returnError('trip or category not found ');
    }

    public function GetTripById($id): JsonResponse
    {
        $trip = Trip::find($id);
        if(isset($trip))
        {
            return $this->returnData('trip information',$trip);
        }else return $this->returnError('Trip not found');
    }

    public function GetAllTrips(): JsonResponse
    {
        $trips = Trip::paginate(15);
        if(isset($trips))
        {
            return $this->returnData('all trips ',$trips);
        }else return $this->returnError('Trips not found');
    }

    public function GetAllTripsDriverCanAccept( $driver_id): JsonResponse
    {


        $driver = Driver::find($driver_id);
        if(isset($driver))
        {
            if($driver->available)
            {

                $trips = Trip::where([
                                    ['cost', '<=', $driver->balance->amount],
                                    ['category_id', '=', $driver->car->category->id],
                                    ['accepted', '=', false],
                                    ['canceled', '=', false],
                                    ['confirmed', '=', true]
                                ])->get();


                                foreach ($trips as $key => $trip)
                                {
                                    ///remove trips which not Belongs to Circle searching
                                    if( !$this->BelongsToCircle(100, $trip->s_lat, $trip->s_long, $driver->lat, $driver->long) )
                                    {
                                        $trips->forget($key);
                                    }
                                }

                return $this->returnData('all trips driver can accept ',$trips);

            }else return $this->returnError("this driver is busy now, can't accept a trip");

        }else return $this->returnError('driver not found');
    }

    public function GetUserTrips($id): JsonResponse
    {
        $user = User::find($id);
        if(isset($user))
        {
            $trips = $user->trips()->paginate(15);
            return $this->returnData('user trips',$trips);
        }else return $this->returnError('User not found');
    }

    public function GetDriverTrips($id): JsonResponse
    {
        $driver = Driver::find($id);
        if(isset($driver))
        {
            $trips = $driver->trips()->paginate(15);
            return $this->returnData('driver trips',$trips);
        }else return $this->returnError('driver not found');
    }

    public function GetAllActiveTrips(): JsonResponse
    {
        $trips = Trip::where('started','=',true)->paginate(15);
        if(isset($trips))
        {
            return $this->returnData('all trips ',$trips);
        }else return $this->returnError('Trips not found');
    }

    public function AcceptTrip($id): JsonResponse
    {
        $trip = Trip::find($id);
        if(isset($trip))
        {
            if($trip->confirmed)
            {
                if(!$trip->canceled)
                {
                    if(!$trip->accepted)
                    {
                        $driver = auth()->user()->driver;
                        if(isset($driver))
                        {
                            ///change status of Driver
                            $driver->available = false;
                            $driver->save();
                            //store rest information of driver in $user
                            $user = $driver->user;
                            ////update trips
                            $trip->accepted = true;
                            $trip->driver_id = $driver->id;
                            $trip->driver_name = $user->name;
                            $trip->driver_phone = $user->phone;
                            $trip->driver_image = $user->image;
                            $trip->save();
                            //send notification to client
                            $this->sendnotification($trip->user->fcm_token,'Trip Accepted','your trip is accepted you can check it  in app ');
                            return $this->returnData('trip is accepted',$trip);

                        }else return $this->returnError('your are not driver');
                    }else return $this->returnError('trip is already accepted ');
                }else return $this->returnError('trip is canceled');
            }else return $this->returnError('Trip not confirmed');

        }else return $this->returnError('trip not found');
    }

    public function  SetTrackingTrip(Request $request): JsonResponse
    {
        $request->validate([
            'trip_id' => 'required|int',
            'long' => 'required',
            'lat' => 'required'
        ]);
        $trip = Trip::find($request->trip_id);
        if(isset($trip))
        {
            if(!$trip->ended)
            {
                if($trip->started)
                {
                    ///create position trip
                    $pos = new Position();
                    $pos->trip_id = $trip->id;
                    $pos->lat = $request->lat;
                    $pos->long = $request->long;
                    $pos->save();
                    ///attach pos with trip
                    $trip->position()->save($pos);
                    $notification = 'Lat : '.$pos->lat.'                    Long : '.$pos->long;
                    $this->sendnotification($trip->user->fcm_token,'Tracking Trip',$notification);
                    return $this->returnSuccessMessage();
                }return $this->returnError("this trip isn't started yet");
            }else return $this->returnError('this trip is ended');
        }else return $this->returnError('this trip not found');
    }

    /**
     * Start the specified Trip and update it information in Storage.
     * and send notification to customer.
     *
     * @param  int  $trip_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function StartTrip($trip_id): JsonResponse
    {
        $driver = auth()->user()->driver;
        if(isset($driver))
        {
            $trip = Trip::find($trip_id);
            if(isset($trip))
            {
                if(!$trip->started)
                {
                    if(!$trip->canceled)
                    {
                        $trip->started = true;
                        $trip->save();
                        $this->sendnotification($trip->user->fcm_token,'Trip Started','Your Trip is started now ');
                        return  $this->returnSuccessMessage('trip is started now');
                    }else return $this->returnError('this trip is cancel');
                }else return $this->returnError('this trip already started');

            }else return $this->returnError('trip not found');

        }else return $this->returnError('you are not driver');
    }
    /**
     * End the specified Trip and update it information in Storage.
     * and discount cost trip from driver balance.
     * and send notification to customer.
     *
     * @param  int  $trip_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function EndTrip($trip_id): JsonResponse
    {
        $driver = auth()->user()->driver;
        if(isset($driver))
        {
            $trip = Trip::find($trip_id);
            if(isset($trip))
            {
                if($trip->started)
                {
                    if(!$trip->ended)
                    {
                        $pos = $trip->position;
                        if($pos->isEmpty())
                        {

                            ///make driver available
                            $driver->available = true;
                            $driver->save();
                            //calculate real distance
                            $distance = 0;
                            for($i = 0 ; $i <  count($pos)-1 ; $i++ )
                            {
                                    $distance += $this->DistanceBetweenTowPoint($pos[$i]->lat,$pos[$i]->long,$pos[$i+1]->lat,$pos[$i+1]->lat,'m');
                            }
                            //update information of trip
                            $trip->ended = true;
                            $trip->distance = $distance;
                            $trip->save();
                            //discount 10% of cost trip from driver
                            $balance = $driver->balance;
                            $balance->amount -= ($trip->cost * 10)/100;
                            $balance->save();
                            /// Send notification to user
                            $this->sendnotification($trip->user->fcm_token,'Trip Ended','Your Trip is Ended now and its cost is :'.$trip->cost);
                            return  $this->returnSuccessMessage();
                        }else return $this->returnError('this trip have no points');
                    }else return $this->returnError('this trip already ended');
                }else return $this->returnError("this trip didn't started yet");

            }else return $this->returnError('trip not found');

        }else return $this->returnError('you are not driver');
    }


}
