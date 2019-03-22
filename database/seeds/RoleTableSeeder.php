<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = 'Platform Admin';
        $role_employee->description = 'A Platform Admin';
        $role_employee->save();
        $role_manager = new Role();
        $role_manager->name = 'Organization Admin';
        $role_manager->description = 'A Organization Admin';
        $role_manager->save();
    }
}
