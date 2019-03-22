<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
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
            DB::table('users')->insert([
                'organization_id' => rand('2', '3'),
                'becon_major_id' => rand('1', '2'),
                'name' => $faker->name,
                'password' => bcrypt('123456'),
                'contact_number' => $faker->phoneNumber,
                'email' => $faker->email,
                'remember_token' => str_random(10),
                'user_type' => 'Security',
                'invitation_status' => 'pending',
                'otp' => str_random(5),
                'status' => 'Active',
                'otp_created_at' => $faker->DateTime,
                'last_login' => $faker->DateTime,
                'created_at' => $faker->DateTime
            ]);
        }
    }
}
