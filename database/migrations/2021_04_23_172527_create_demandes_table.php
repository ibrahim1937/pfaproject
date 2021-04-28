<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_fonctionnaire')->constrained('users');
            $table->foreignId('id_etudiant')->constrained('etudiants');
            $table->foreignId('id_etat')->constrained('etats');
            $table->foreignId('id_category')->constrained('category_demandes');
            $table->string('message')->nullable();
            $table->string('message_reponse')->nullable();
            $table->date('Date_livraison')->nullable();
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
        Schema::dropIfExists('demandes');
    }
}
