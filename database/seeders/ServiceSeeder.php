<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Swedish Massage', 'description' => null, 'price' => 380, 'duration' => 60],
            ['name' => 'Kiddie Massage', 'description' => null, 'price' => 300, 'duration' => 45],
            ['name' => 'Foot Massage', 'description' => null, 'price' => 350, 'duration' => 45],
            ['name' => 'Signature Massage', 'description' => null, 'price' => 500, 'duration' => 90],
            ['name' => 'Back Massage', 'description' => null, 'price' => 199, 'duration' => 30],

            // Combos
            ['name' => 'Swedish Massage w/ Head & Facial Massage', 'description' => null, 'price' => 550, 'duration' => 90],
            ['name' => 'Swedish Massage w/ Hot stone', 'description' => null, 'price' => 450, 'duration' => 75],
            ['name' => 'Swedish Massage w/ Ventosa', 'description' => null, 'price' => 480, 'duration' => 75],
            ['name' => 'Swedish Massage w/ Foot Massage', 'description' => null, 'price' => 400, 'duration' => 75],
            ['name' => 'Signature Massage w/ Head & Facial Massage', 'description' => null, 'price' => 700, 'duration' => 120],
            ['name' => 'Signature Massage w/ Hot Stone', 'description' => null, 'price' => 600, 'duration' => 105],
            ['name' => 'Signature Massage w/ Ventosa', 'description' => null, 'price' => 650, 'duration' => 105],
            ['name' => 'Signature Massage w/ Foot Massage', 'description' => null, 'price' => 780, 'duration' => 130],
            ['name' => 'Head & Facial Massage w/ Foot Massage', 'description' => null, 'price' => 400, 'duration' => 75],
            ['name' => 'Kiddie Massage w/ Foot Massage', 'description' => null, 'price' => 380, 'duration' => 60],
            ['name' => 'Kiddie Massage w/ Hot Stone', 'description' => null, 'price' => 350, 'duration' => 60],
            ['name' => 'Back Massage w/ 30 mins Foot Massage', 'description' => null, 'price' => 350, 'duration' => 60],
            ['name' => 'Back w/ Head & Facial Massage', 'description' => null, 'price' => 290, 'duration' => 60],
            ['name' => 'Back Massage w/ Hot Stone', 'description' => null, 'price' => 300, 'duration' => 60],
            ['name' => 'Back Massage w/ Ventosa', 'description' => null, 'price' => 350, 'duration' => 60],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(
                ['name' => $s['name']],
                ['description' => $s['description'], 'price' => $s['price'], 'duration' => $s['duration'], 'is_active' => true]
            );
        }
    }
}
