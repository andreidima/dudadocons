<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Membru;

class MembruFactory extends Factory
{
    protected $model = Membru::class;

    public function definition()
    {
        // Job titles specific to architecture, engineering, and construction supervision
        $jobTitles = [
            'Arhitect', 'Inginer Structurist', 'Inginer Instalații',
            'Inginer Electrician', 'Inginer Geotehnician', 'Manager de Proiect',
            'Tehnician CAD', 'Urbanist', 'Inginer Construcții Civile',
            'Inspector de Șantier', 'Consultant Tehnic', 'Specialist Siguranță și Sănătate în Muncă',
            'Expert Evaluator', 'Director Tehnic', 'Asistent Proiectare'
        ];

        // Departments within an architecture and engineering consultancy
        $departments = [
            'Arhitectură', 'Inginerie Structurală', 'Instalații', 'Proiectare Tehnică',
            'Management Proiecte', 'Supervizare Șantier', 'Evaluare Tehnică',
            'Urbanism și Planificare', 'Siguranță și Sănătate în Muncă'
        ];

        return [
            'nume' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'telefon' => $this->faker->phoneNumber,
            'adresa' => $this->faker->address,
            'functie' => $this->faker->randomElement($jobTitles), // Industry-specific job title
            'departament' => $this->faker->randomElement($departments), // Relevant department
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
