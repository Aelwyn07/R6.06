<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_creation()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\RoleSeeder::class
        ]);
        
        $role = Role::first();

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('administrator', $role->name);
        $this->assertEquals(0, $role->level);
    }

    public function test_role_relationships()
    {
        // Exécuter les seeders nécessaires
        $this->seed([
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\UserSeeder::class
        ]);
        
        $role = Role::with('users')->where('name', 'administrator')->first();

        // Test de la relation avec Users
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $role->users);
        $this->assertInstanceOf(User::class, $role->users->first());
        $this->assertCount(1, $role->users); // Car un seul utilisateur admin dans le seeder
    }

    public function test_role_validation()
    {
        // Test de création avec des données valides
        $role = Role::create([
            'name' => 'Test Role',
            'level' => 1
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Test Role',
            'level' => 1
        ]);

        // Test de création avec des données invalides
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Role::create([
            'name' => null, // Le nom ne peut pas être null
            'level' => 'invalid' // Le niveau doit être un nombre
        ]);
    }
} 