<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Offre;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 15 competences uniques
        $competences = Competence::factory(15)->create();
        $niveaux = ['debutant', 'intermediaire', 'expert'];

        // 2 admins
        User::factory(2)->create([
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 5 recruteurs avec 2-3 offres chacun
        User::factory(5)->create([
            'password' => Hash::make('password'),
            'role' => 'recruteur'

        ])->each(function (User $recruteur) {
            Offre::factory(rand(2, 3))->create(['user_id' => $recruteur->id]);
        });

        // 10 candidats avec profil et competences
        User::factory(10)->create([
            'password' => Hash::make('password'),
            'role'     => 'candidat',
        ])->each(function (User $candidat) use ($competences, $niveaux) {
            $profil = Profil::factory()->create(['user_id' => $candidat->id]);

            // 3–5 competences aleatoires avec un niveau
            $selected = $competences->random(rand(3, 5));
            $pivot    = $selected->mapWithKeys(fn($c) => [
                $c->id => ['niveau' => $niveaux[array_rand($niveaux)]]
            ])->toArray();

            $profil->competences()->attach($pivot);
        });
    }
}
