<?php

namespace App\Listeners;

use App\Events\PembayaranLimitTime;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PembayaranLimitTimeListener implements ShouldQueue
{
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
    public function handle(PembayaranLimitTime $event)
    {
        // $event = $this->time;
        print_r($this);
        $start = date('H:i:s', strtotime($event->created_at));
        $end   = date('H:i:s', strtotime('+1 minutes', strtotime($event->created_at)));
        $status = true;
        while($status){
            sleep(1);
            $now = Carbon::now('Asia/Singapore');
            $now->addMinute(0);
            $timeNow  = $now->format('H:i:s');
            // print(date('H:i:s', strtotime('+10 minutes', strtotime($time->created_at))));
            if ($timeNow >= $start && $timeNow <= $end) {
                
                
                continue;
            }else{
                print($timeNow);
                $status = false;
            }
            
        }
        
    }
}
