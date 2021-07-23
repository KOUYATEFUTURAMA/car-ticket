<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChauffeursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id();
            $table->string('full_name_chauffeur');
            $table->string('civilite');
            $table->string('numero_permis');
            $table->string('groupe_sanguin');
            $table->string('adresse_chauffeur');
            $table->string('contact_chauffeur');
            $table->date('date_prise_service');
            $table->date('date_naissance');
            $table->date('date_fin_permis');
            $table->string('contact_en_cas_urgence');
            $table->integer('compagnie_id');
            $table->string('contact_conjoint')->nullable();
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
        Schema::dropIfExists('chauffeurs');
    }
}
