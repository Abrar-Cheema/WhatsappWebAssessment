<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(CountriesStatesCitiesSeeder::class);
        $this->call(TypesSeeder::class);
        $this->call(StylesSeeder::class);
        $this->call(FeaturesSeeder::class);
        $this->call(RuleSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(CategorySeeder::class);
        \App\Models\User::factory(10)->create();
        $this->call(TypeFeatureSeeder::class);
    }
}
