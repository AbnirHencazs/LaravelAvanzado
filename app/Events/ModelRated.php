<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelRated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $qualifier;
    public $rateable;
    public $score;
    /**
     * Create a new event instance.
     * 
     * @param Model el modelo del calificador
     * @param Model el modelo que fue calificado
     * @param float valor de la calificación
     * @return void
     */
    public function __construct( Model $qualifier, Model $rateable, float $score )
    {
        //Vamos a indicar los objetos que intervienen dentro de mi modelo
        $this->qualifier = $qualifier;
        $this->rateable = $rateable;
        $this->score = $score;
    }

    /**
     * Creamos getters para las propiedades asignadas en el constructor
     * Los obtendremos cada vez que el evento se transmita
     */
    public function getRateable(): Model
    {
        return $this->rateable;
    }

    public function getQualifier(): Model
    {
        return $this->qualifier;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * Get the channels the event should broadcast on.
     * sirve para cuando tenemos mensajes de broadcasting
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
