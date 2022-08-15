<?php

namespace App\Jobs;

use App\Models\Driver;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchAboutDriver implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,GeneralTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $trip_id,$category_id;
    public $tries  = 5;
    public function __construct($trip_id,$category_id)
    {
        $this->trip_id = $trip_id;
        $this->category_id = $category_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $noti = new Notification();
        $noti->title = "a";
        $noti->body = "one piece";
        $noti->save();
       $this->release(5);

    }
}
