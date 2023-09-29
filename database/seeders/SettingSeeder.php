<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->truncate();

        DB::table('settings')->insert([
            [
                'type' => 'site',
                'name' => 'Site Name',
                'slug' => 'site_name',
                'value' => 'The Magnet'
            ], 
            [
                'type' => 'site',
                'name' => 'Site URL',
                'slug' => 'site_url',
                'value' => 'https://the-magnet.netlify.app/'
            ],
            [
                'type' => 'site',
                'name' => 'Asset Url',
                'slug' => 'asset_url',
                'value' => 'https://the-magnet.netlify.app/storage'
            ]
        ]);
    }
}
