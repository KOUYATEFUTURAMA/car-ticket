<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departs', function (Blueprint $table) {
            $table->id();
            $table->integer('localite_depart');
            $table->integer('localite_arrive');
            $table->integer('tarif');
            $table->integer('place_disponible');
            $table->integer('place_vendue')->default(0);
            $table->integer('vehicule_id');
            $table->integer('chauffeur_id');
            $table->dateTime('date_depart');
            $table->dateTime('date_arrivee');
            $table->string('statut');
            $table->integer('compagnie_id');
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departs');
    }
}
