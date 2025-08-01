<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user = User::firstOrCreate([
            'email' => 'admin@biterush.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('admin123'),
        ]);

        $user->role = 'admin';
        $user->is_admin = true;
        $user->save();
    }
}
