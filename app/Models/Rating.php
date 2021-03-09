<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Rating extends Pivot //Como es una tabla intermedia heredamos de Pivot en lugar de model
{
    use HasFactory;

    public $incrementing = true; //indicamos campos autoincrementables

    protected $table = 'ratings'; //Indicamos explicitamente a que tabla se relaciona

    public function rateable()
    {
        return $this->morphTo();
    }

    public function qualifier()
    {
        return $this->morphTo();
    }
}
