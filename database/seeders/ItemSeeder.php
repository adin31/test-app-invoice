<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Item::truncate();

        $data = [
            [
                'name'          => 'Design',
                'type'          => 'Service',
                'description'   => 'Design',
                'unit_price'    => 230,
            ],
            [
                'name'          => 'Development',
                'type'          => 'Service',
                'description'   => 'Development',
                'unit_price'    => 330,
            ],
            [
                'name'          => 'Meetings',
                'type'          => 'Service',
                'description'   => 'Meetings',
                'unit_price'    => 60,
            ],
        ];
        
		foreach ($data as $dt) {
			Item::create($dt);
		}
        Schema::enableForeignKeyConstraints();
    }
}
