<?php

namespace App\Utils;

trait CanBeRated
{
    public function qualifiers( string $model = null )
    {
        $modelClass = $model ? (new $model)->getMorphClass() : $this->getMorphClass();

        $morphToMany = $this->morphToMany(
            $modelClass,
            'rateable',
            'ratings',
            'rateable_id',
            'qualifier_id'
        );

        $morphToMany
            ->withPivot( 'qualifier_type', 'score' )
            ->wherePivot( 'qualifier_type', $modelClass )
            ->wherePivot( 'rateable_type', $this->getMorphClass() );

        return $morphToMany;
    }

    /**
     * @param string nombre de la clase del modelo que puede ser nulo
     */
    public function averageRating( string $model = null )
    {
        return $this->qualifiers( $model )->avg('score' ) ? : 0.0;
    }
}