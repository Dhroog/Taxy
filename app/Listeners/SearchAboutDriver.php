<?php

namespace App\Listeners;

use App\Events\SearchAboutDrivers;
use App\Models\Driver;
use App\Models\Trip;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SearchAboutDriver
{
    use GeneralTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SearchAboutDrivers $event)
    {
        $trip = Trip::find($event->trip_id);
        if(isset($trip))
        {
            $accepted = false;
            $now = Carbon::now();
            $after3minutes = $now->addMinutes(0);
            while( $now->diffInMinutes($after3minutes) < 2 || $accepted	 == false )
            {
                ///////get all available drivers
                $drivers = Driver::where('available',true)->get();
                foreach ($drivers as $driver)
                {
                    //check if driver Belongs To Circle customer
                    if( $this->BelongsToCircle(100,$trip->s_lat,$trip->s_long,$driver->lat,$driver->long) && $driver->car->category->id == $event->category_id )
                    {
                        ///check if driver not reject this trip before
                        $rejections = $driver->rejection;
                        $re = true;
                        if(isset($reject))
                        {
                            foreach ($rejections as $value)
                            {
                                if( $value->trip_id == $event->trip_id )
                                {
                                    $re = false;
                                    break;
                                }
                            }
                        }
                        ////send notification to driver
                        if($re)
                        {
                            $this->sendnotification($driver->user->fcm_token,'Trip Available ','there new trip can accept it');
                        }
                    }
                }

                $trip = Trip::find($event->trip_id);
                $accepted = $trip->accepted;

                //sleep(30);
                $now = Carbon::now();
            }
        }
    }
}
