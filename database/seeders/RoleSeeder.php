<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nom' => 'Choriste',
                'description' => 'Membre du chœur, participe aux répétitions et concerts',
                'permissions' => json_encode(['participation_repetitions', 'participation_concerts']),
                'niveau_priorite' => 1
            ],
            [
                'nom' => 'Soliste',
                'description' => 'Chanteur principal, interprète les solos',
                'permissions' => json_encode(['participation_repetitions', 'participation_concerts', 'interpretation_solos']),
                'niveau_priorite' => 2
            ],
            [
                'nom' => 'Responsable',
                'description' => 'Responsable d\'une section ou du groupe',
                'permissions' => json_encode(['gestion_section', 'coordination_activites']),
                'niveau_priorite' => 3
            ],
            [
                'nom' => 'Musicien',
                'description' => 'Joue d\'un instrument dans le groupe',
                'permissions' => json_encode(['participation_repetitions', 'participation_concerts', 'interpretation_instrumentale']),
                'niveau_priorite' => 2
            ],
            [
                'nom' => 'Technicien',
                'description' => 'S\'occupe de la technique (son, éclairage)',
                'permissions' => json_encode(['gestion_technique', 'maintenance_equipements']),
                'niveau_priorite' => 2
            ],
            [
                'nom' => 'Administrateur',
                'description' => 'Gère l\'administration du groupe',
                'permissions' => json_encode(['gestion_membres', 'gestion_cotisations', 'gestion_evenements', 'administration_generale']),
                'niveau_priorite' => 4
            ]
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }
    }
}
