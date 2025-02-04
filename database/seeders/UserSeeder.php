<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Création de l'admin avec son profil enseignant
        User::factory()->admin()->create([
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'System',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123')
        ]);

        // Création du lecteur avec son profil enseignant
        User::factory()->reader()->create([
            'username' => 'reader',
            'firstname' => 'Basic',
            'lastname' => 'Reader',
            'email' => 'reader@example.com',
            'password' => bcrypt('reader123')
        ]);


        // Création du lecteur étendu avec son profil enseignant
        User::factory()->extendedReader()->create([
            'username' => 'extended',
            'firstname' => 'Extended',
            'lastname' => 'Reader',
            'email' => 'extended@example.com',
            'password' => bcrypt('extended123')
        ]);
    }
}
