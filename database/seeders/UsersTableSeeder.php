<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\User;
use App\Models\Writer;
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

        $admin = Admin::create(['credential_number' => '12345678A']);
        $admin->user()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('adminadmin'),
            'role' => User::ROLE_SUPERADMIN,
        ]);

        Writer::factory(10)->create()->each(function ($writer) {
            $writer->user()->create(array_merge(
                User::factory()->make()->toArray(),
                ['password' => Hash::make('12345678')]
            ));

            $writer->user->categories()->saveMany(
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

        // User::factory(10)->create()->each(function ($user) {
        //     $user->categories()->saveMany(
        //         fake()->randomElements(
        //             [
        //                 Category::find(1),
        //                 Category::find(2),
        //                 Category::find(3),
        //             ],
        //             fake()->numberBetween(1, 3)
        //         )
        //     );
        // });
    }
}
