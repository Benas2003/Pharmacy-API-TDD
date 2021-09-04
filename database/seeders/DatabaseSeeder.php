<?php

namespace Database\Seeders;

use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Administrator']);
        $role2 = Role::create(['name' => 'Pharmacist']);
        $role3 = Role::create(['name' => 'Department']);

        $admin = User::create([
            'name'=>'Admin',
            'email'=>'admin@icloud.com',
            'password'=>bcrypt('Admin'),
        ]);

        $pharmacist = User::create([
            'name'=>'Jonas Jonaitis',
            'email'=>'jonas.jonaitis@icloud.com',
            'password'=>bcrypt('JJKK'),
        ]);

        $department = User::create([
            'name'=>'Centrinė reanimacija (91)',
            'email'=>'cr@icloud.com',
            'password'=>bcrypt('CR91'),
        ]);

        $admin->assignRole($role1);
        $pharmacist->assignRole($role2);
        $department->assignRole($role3);

        Product::factory(10)->create();
        $consignments = Consignment::factory(3)->create([
            'department_id'=>$department->id,
            'status'=>'Created'
        ]);

        foreach ($consignments as $consignment)
        {
            ConsignmentProduct::factory(2)->create([
                'consignment_id'=>$consignment->id
            ]);
        }
    }
}
