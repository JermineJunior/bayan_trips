<?php

namespace Database\Seeders;

use App\Models\TripType;
use Illuminate\Database\Seeder;

class TripTypeSeeder extends Seeder
{
    public function run(): void
    {
        TripType::firstOrCreate(
            ['is_default' => true],
            ['name' => 'رحلة عامة']
        );
    }
}
