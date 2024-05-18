<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'statistics_admin', //1
            'statiststaff',  //2
            'Browse Restaurants', //3
            'Crud Restaurants', //4
            'Users Management', //5
            'Browse Cuisines', //6
            'Crud Cuisines', //7
            'Browse Reservation', //8
            'browse Reservation_Records', //9
            'edit_record', //10
            'start_end_reservation', //11
            'edit_delete_reservation', //12
            'Reservations_Generate', //13
            'Add Reservation', //14
            'Crud Tables', //15
            'Browse Tables(staff)', //16
            'promoSystem', //17
            'invitationSystem', //18
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $AdminPermissions = ['1','3', '4', '5', '6', '7', '10', '11', '12', '13', '14', '15', '17', '18'];
        $permissions = Permission::whereIn('id', $AdminPermissions)->get();
        $role = Role::create(['name' => 'admin']);
        $role->syncPermissions($permissions);
        $StaffPermissions = ['2', '8', '9', '11', '16'];
        $permissions = Permission::whereIn('id', $StaffPermissions)->get();
        $role = Role::create(['name' => 'staff']);
        $role->syncPermissions($permissions);
    }
}
