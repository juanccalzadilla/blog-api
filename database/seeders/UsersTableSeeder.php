<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('adminadmin'),
        ]);
        
        User::factory(10)->create()->each(function ($user) {
            $user->categories()->saveMany(
                fake()->randomElements(
                    [
                        Category::find(1),
                        Category::find(2),
                        Category::find(3),
                    ],
                    fake()->numberBetween(1, 3)
                )
            );
        });

        
    }

}
