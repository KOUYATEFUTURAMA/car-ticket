<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->text('descritpion_vehicule');
            $table->string('immatriculation');
            $table->integer('nombre_place');
            $table->integer('marque_id');
            $table->integer('moteur_id');
            $table->integer('puissance_id');
            $table->integer('type_vehicule_id');
            $table->string('type_vitesse');
            $table->date('date_next_visite');
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
        Schema::dropIfExists('vehicules');
    }
}
