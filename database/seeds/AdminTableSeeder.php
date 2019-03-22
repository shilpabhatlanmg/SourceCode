<?php
use Illuminate\Database\Seeder;
use App\Admin;
use App\Role;

class AdminTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $role_platform_admin = Role::where('name', 'Platform Admin')->first();

        $role_org_admin = Role::where('name', 'Organization Admin')->first();
        
        $super_admin = new Admin();
        $super_admin->name = 'Rahul Mehta';
        $super_admin->username = 'rahul';
        $super_admin->email = 'rahulm.nmg@yopmail.com';
        $super_admin->password = bcrypt('123456');
        $super_admin->status = '1';
        $super_admin->save();
        $super_admin->roles()->attach($role_platform_admin);

        $admin = new Admin();
        $admin->name = 'Sandeep Kumar';
        $admin->username = 'sandeep';
        $admin->email = 'sandeepk.nmg@yopmail.com';
        $admin->password = bcrypt('123456');
        $admin->status = '1';
        $admin->save();
        $admin->roles()->attach($role_org_admin);

    }
}
