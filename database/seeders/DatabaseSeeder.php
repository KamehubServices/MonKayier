<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with essential controlled roles and Togo referentials.
     */
    public function run(): void
    {
        $roles = [
            ['code' => 'student', 'label' => 'Élève', 'description' => 'Accès aux cours, fiches, quiz et assistant IA'],
            ['code' => 'teacher', 'label' => 'Enseignant', 'description' => 'Création de devoirs et corrections assistées'],
            ['code' => 'parent', 'label' => 'Parent / Tuteur', 'description' => 'Suivi de la progression et des échéances'],
            ['code' => 'school_admin', 'label' => 'Administrateur d\'Établissement', 'description' => 'Gestion scolaire'],
            ['code' => 'support', 'label' => 'Support et Modération', 'description' => 'Assistance aux utilisateurs'],
            ['code' => 'solidarity_agent', 'label' => 'Agent de Solidarité', 'description' => 'Instruction des demandes de bourses'],
            ['code' => 'admin', 'label' => 'Administrateur Système', 'description' => 'Administration technique globale'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['code' => $r['code']], $r);
        }

        $this->call(TogoCurriculaSeeder::class);
        $this->call(AssessmentSeeder::class);
    }
}
