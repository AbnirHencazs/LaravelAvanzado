<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Esta sera una tabla intermedia (pivote) que relacionara muchos ratings con muchos usuarios
         */
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->float('score'); //guardamos la puntuacion
            $table->timestamps();

            //columnas polimorficas
            /**
             * Dentro de un tabla polimorfica es necesario tener un id y un tipo
             * para saber con que modelo me voy a conectar, ejemplo:
             * App\Models\Product con id 5, significa el registro 5 de la tabla products
             */
            $table->morphs( 'rateable' );
            //seria lo mismo que escribieramos
            //$table->unsignedBigInteger('rateable_id')
            //$table->string('rateable_type')

            /**
             * En este caso la tabla intermedia tiene con quien tiene relacionado los usuarios
             * para identificar la entidad que esta calificando (usuario, invitado, sesion, etc)
             */
            $table->morphs( 'qualifier' );
            /**
             * seria lo mismo que escribir:
             * $table->unsignedBigInsteger('quelifier_id')
             * $table->string('qualifier_type')
             */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
