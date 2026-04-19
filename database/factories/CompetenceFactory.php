<?php

namespace Database\Factories;

use App\Models\Competence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Competence>
 */
class CompetenceFactory extends Factory
{
    private static array $competences = [
        'PHP',
        'Laravel',
        'JavaScript',
        'CSS',
        'HTML',
        'Linux',
        'REST',
        'MySQL',
        'React',
        'Python',
        'AWS',
        'Symfony',
        'PostgreSQL',
        'Spring Boot',
        'Django'
    ];

    private static array $categories = ['Backend', 'Frontend', 'DevOps', 'Database', 'Cloud'];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->randomElement(self::$competences),
            'categorie' => $this->faker->randomElement(self::$categories),
        ];
    }
}
