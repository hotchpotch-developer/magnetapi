<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('states')->truncate();

        DB::table('states')->insert([
            [
                "name"=> "Andaman & Nicobar Islands"
            ],
            [
                "name"=> "Andhra Pradesh"
            ],
            [
                "name"=> "Arunachal Pradesh"
            ],
            [
                "name"=> "Assam"
            ],
            [
                "name"=> "Bihar"
            ],
            [
                "name"=> "Chandigarh"
            ],
            [
                "name"=> "Chhattisgarh"
            ],
            [
                "name"=> "Dadra & Nagar Haveli"
            ],
            [
                "name"=> "Daman & Diu"
            ],
            [
                "name"=> "Delhi"
            ],
            [
                "name"=> "Goa"
            ],
            [
                "name"=> "Gujarat"
            ],
            [
                "name"=> "Haryana"
            ],
            [
                "name"=> "Himachal Pradesh"
            ],
            [
                "name"=> "Jammu & Kashmir"
            ],
            [
                "name"=> "Jharkhand"
            ],
            [
                "name"=> "Karnataka"
            ],
            [
                "name"=> "Kerala"
            ],
            [
                "name"=> "Lakshadweep"
            ],
            [
                "name"=> "Madhya Pradesh"
            ],
            [
                "name"=> "Maharashtra"
            ],
            [
                "name"=> "Manipur"
            ],
            [
                "name"=> "Meghalaya"
            ],
            [
                "name"=> "Mizoram"
            ],
            [
                "name"=> "Nagaland"
            ],
            [
                "name"=> "Odisha"
            ],
            [
                "name"=> "Puducherry"
            ],
            [
                "name"=> "Punjab"
            ],
            [
                "name"=> "Rajasthan"
            ],
            [
                "name"=> "Sikkim"
            ],
            [
                "name"=> "Tamil Nadu"
            ],
            [
                "name"=> "Tripura"
            ],
            [
                "name"=> "Uttar Pradesh"
            ],
            [
                "name"=> "Uttarakhand"
            ],
            [
                "name"=> "West Bengal"
            ]
        ]);
    }
}
