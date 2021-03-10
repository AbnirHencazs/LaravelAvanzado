<?php

namespace App\Listeners;

use App\Events\ModelUnrated;
use App\Models\Product;
use App\Notifications\ModelUnratedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailModelUnratedNotification
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
    public function handle( ModelUnrated $event )
    {
        $rateable = $event->getRateable();
        
        if( $rateable instanceof Product ){
            $notification = new ModelUnratedNotification( $event->getQualifier()->name, $event->getRateable()->name );
            
            $rateable->createdBy->notify( $notification );
        }
    }
}
