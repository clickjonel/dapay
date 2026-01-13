<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProgramSeeder::class,
            GeolocationSeeder::class,
            DisaggregationSeeder::class,
            IndicatorSeeder::class,
            BenguetSeeder::class,
            AbraSeeder::class,
            ApayaoSeeder::class,
            IfugaoSeeder::class,
            KalingaSeeder::class,
            MPSeeder::class,
        ]);
    }
}
