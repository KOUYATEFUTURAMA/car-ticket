<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompagniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compagnies', function (Blueprint $table) {
            $table->id();
            $table->string('libelle_compagnie');
            $table->string('adresse_complet');
            $table->string('contact_compagnie');
            $table->string('email_compagnie');
            $table->string('responsable');
            $table->string('contact_responsable');
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('compagnies');
    }
}
