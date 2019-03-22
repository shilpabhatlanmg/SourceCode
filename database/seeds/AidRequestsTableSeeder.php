<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AidRequestsTableSeeder extends Seeder
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
            DB::table('aid_requests')->insert([
                'location_id' => rand(37,38),
                'user_id' => rand(1, 2),
                'incident_type_id' => rand(1, 2),
                'minor_id' => rand(1, 10),
                'status' => 'Active',
                'created_at' => $faker->DateTime
            ]);
        }
    }
}
