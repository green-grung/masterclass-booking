<?php

namespace Database\Seeders;

use App\Models\Craft;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // виды творчества
        $crafts = [
            [
                'name' => 'Архитектурное моделирование',
                'description' => file_get_contents(base_path('database/seeders/data/arch.txt')),
                'image' => 'elifant.png',
            ],
            [
                'name' => 'Кулинария',
                'description' => file_get_contents(base_path('database/seeders/data/cooking.txt')),
                'image' => 'cooking.png',
            ],
            [
                'name' => 'Резьба по дереву',
                'description' => file_get_contents(base_path('database/seeders/data/woodcarving.txt')),
                'image' => 'wood.jpg',
            ],
        ];

        foreach ($crafts as $craft) {
            Craft::create($craft);
        }

        // ведущ
        User::create([
            'name' => 'Иванова Ольга Ивановна',
            'email' => 'master@example.com',
            'password' => Hash::make('password'),
            'phone' => '+79123456789',
            'role' => 'master',
            'photo' => 'driver-page.png',
        ]);

        // обыч юзер
        User::create([
            'name' => 'Тестовый Пользователь',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '+79991234567',
            'role' => 'user',
        ]);
    }
}
