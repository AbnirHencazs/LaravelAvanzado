<?php

namespace App\Utils;

use App\Events\ModelRated;
use App\Events\ModelUnrated;
use Illuminate\Database\Eloquent\Model;

/**
 * Este trait servira para darselo a todos los modelos que puedan calificar
 */
trait CanRate
{
    /**
     * Relacion llamada ratings
     * 
     * @param Model $model recibira un modelo que puede ser nulo
     */
    public function ratings( $model = null )
    {
        /**
         * Si existe el modelo lo paso a $modelClass, si no,
         * obtengo la clase del modelo que este referenciando el trait
         */
        $modelClass = $model ? $model : $this->getMorphClass();
        /**
         * Aqui declaramos la relacion
         * @param Class con la que queremos relacionar el trait
         * @param String nombre de la relacion, como estamos definiendo quien puede calificar, se usa qualifier
         * @param String nombre de la tabla pivote
         * @param String que registro es el que esta calificando 
         * @param String que registro es el que se va a calificar
         */
        $morphToMany = $this->morphToMany(
            $modelClass,
            'qualifier',
            'ratings',
            'qualifier_id',
            'rateable_id'
        );

        /**
         * Geranamos un alias para la relacion
         * indico que trabajaremos con los campos de fecha
         * cada vez que llamo la relacion, retorne los datos score y el modelo que se calificó
         * donde el modelo que se calificó sea igual a la clase que pasamos al trait
         * y donde el modelo que calificó a la clase que invoco la relación
         */
        $morphToMany
            ->as('rating')
            ->withTimeStamps()
            ->withPivot( 'score', 'rateable_type' )
            ->wherePivot( 'rateable_type', $modelClass )
            ->wherePivot( 'qualifier_type', $this->getMorphClass() );

        return $morphToMany;
    }

    /**
     * A traves de este metodo invocaremos la relacion
     */
    public function rate(Model $model, float $score )
    {
        /**
         * Para no tener que calificar dos veces al mismo modelo
         */
        if($this->hasRated($model)){
            return false;
        }
        /**
         * Utilizamos la relacion ratings del Trait CanRate
         * con el metodo attach indico que coloque dentro de la tabla pivote
         * el id que tenga mi modelo relacionado al score que me pasan en este metodo
         */
        $this->ratings($model)->attach($model->getKey(), [
            'score' => $score,
            'rateable_type' => get_class($model)
        ]);

        event( new ModelRated( $this, $model, $score ) ); //disparamos el evento, hace falta que alguien lo escuche

        return true;
    }

    public function unrate( Model $model )
    {
        // $rating = \App\Models\Rating::query()
        //                             ->where( 'qualifier_id', $this->id )
        //                             ->where( 'rateable_id', $model->id )
        //                             ->first();

        // $rating->delete();

        $this->ratings( $model->getMorphClass() )->detach( $model->getKey() );

        event( new ModelUnrated( $this, $model ) );

        return true;
    }

    public function hasRated(Model $model)
    {
        /**
         * Usamos la relacion ratings para encontrar la tupla que tenga
         * el qualifier_type y qualifier_id que le pasamos, si no existe,
         * entonces se puede calificar, si lo encuentra, entonces ya ha sido
         * calificado
         */
        return ! is_null( $this->ratings($model->getMorphClass())->find($model->getKey()) );
    }
}