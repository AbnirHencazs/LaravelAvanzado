<?php

namespace App\Listeners;

use App\Events\ModelRated;
use App\Models\Product;
use App\Notifications\ModelRatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailModelRatedNotification
{
    /**
     * Create the event listener.
     * Un listener esta compuesto por el constructor en el cual
     * le puedo pasar por inyeccion de dependencia cualquier
     * servicio que requiera
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * Logica de negocio que yo quiero que se ejecute cuando un evento se dispare
     *
     * @param  object  $event
     * @return void
     */
    public function handle( ModelRated $event )
    {
        $rateable = $event->getRateable();
        if( $rateable instanceof Product ){ //solo queremos enviar el email cuando la entidad calificada sea un producto
            $notification = new ModelRatedNotification( $event->getQualifier()->name,
                                                        $event->getRateable()->name,
                                                        $event->getScore()  );

            $rateable->createdBy->notify( $notification );
        }
    }
}
