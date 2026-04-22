<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador padrão
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@tasklean.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'idioma' => 'pt-BR',
        ]);

        // Criar usuário de teste
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@tasklean.com',
            'username' => 'teste',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'idioma' => 'pt-BR',
        ]);
    }
}
