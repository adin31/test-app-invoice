<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();

        $data = [
            [
                'name'              => 'Admin',
                'email'             => 'admin@test.com',
                'email_verified_at' => now()->toDateTimeString(),
                'password'          => Hash::make('password'),
            ],
        ];
        
		foreach ($data as $dt) {
			User::create($dt);
		}
        Schema::enableForeignKeyConstraints();
    }
}
