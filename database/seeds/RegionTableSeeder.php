<?php

use App\Entity\Regions;
use Illuminate\Database\Seeder;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Regions::class, 10)->create()->each(function(Regions $region) {
            $region->children()->saveMany(factory(Regions::class, random_int(3, 10))->create()->each(function(Regions $region) {
                $region->children()->saveMany(factory(Regions::class, random_int(3, 10))->make());
            }));
        });
    }
}
