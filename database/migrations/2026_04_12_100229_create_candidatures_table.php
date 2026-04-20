<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Cree la table des candidatures. */
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained()->onDelete('cascade');
            $table->foreignId('profil_id')->constrained()->onDelete('cascade');
            $table->text('message')->nullable();
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
            // Empeche un meme profil de postuler deux fois a la meme offre.
            $table->unique(['offre_id', 'profil_id']);
            $table->timestamps();
        });
    }

    /** Supprime la table des candidatures. */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
