<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Cree la table des offres. */
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Identifiant du recruteur proprietaire.
            $table->string('titre');
            $table->text('description');
            $table->string('localisation')->nullable();
            $table->enum('type', ['CDI', 'CDD', 'stage']);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /** Supprime la table des offres. */
    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
