<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VisitorLogTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 50) as $index) {
            DB::table('visitor_logs')->insert([
                'location_id' => rand(29,36),
                'user_id' => rand(1, 2),
                'status' => 'Active',
                'created_at' => $faker->DateTime
            ]);
        }
    }
}
