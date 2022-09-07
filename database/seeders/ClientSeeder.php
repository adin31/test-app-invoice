<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Client::truncate();

        $data = [
            [
                'name'      => 'Discovery Design',
                'address'   => '41 St Vincent place Glassgow G1 Scotlang',
            ],
            [
                'name'      => 'Barrington Publisher',
                'address'   => '17 Great Suffolk Street London SE1 0NS United Kingdom',
            ],
        ];
        
		foreach ($data as $dt) {
			Client::create($dt);
		}
        Schema::enableForeignKeyConstraints();
    }
}
