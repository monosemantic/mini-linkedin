<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Cree la table des competences. */
    public function up(): void
    {
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('categorie')->nullable();
            $table->timestamps();
        });
    }

    /** Supprime la table des competences. */
    public function down(): void
    {
        Schema::dropIfExists('competences');
    }
};
