<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class DefaultRoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard',

            'vendor.order.status',

            'vendor.food.view',
            'vendor.food.create',
            'vendor.food.update',
            'vendor.food.delete',
            'vendor.food.set_meal',
            'vendor.food.stock_update',

            'vendor.meal_history',
            'vendor.payment_history',

            'employee.make_order',
            'employee.vendor_list',
            'employee.place_order',

            'employee.order.status',
            'employee.order.cancel',

            'employee.payment_history',

            'office_staff.order.status',


            'food_category.view',
            'food_category.edit',
            'food_category.create',
            'food_category.delete',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            'team.view',
            'team.create',
            'team.edit',
            'team.delete',

            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            'settings.view',
            'settings.edit',

            'profile.update'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $roles = ['admin', 'vendor', 'employee', 'office_staff'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }


        Role::findByName('admin')
            ->givePermissionTo(Permission::all());

        Role::findByName('vendor')->givePermissionTo([
            'dashboard',
            'vendor.order.status',

            'vendor.food.view',
            'vendor.food.create',
            'vendor.food.update',
            'vendor.food.delete',
            'vendor.food.set_meal',
            'vendor.food.stock_update',

            'vendor.meal_history',
            'vendor.payment_history',
            'profile.update'
        ]);

        Role::findByName('employee')->givePermissionTo([
            'dashboard',
            'employee.make_order',
            'employee.vendor_list',
            'employee.place_order',

            'employee.order.status',
            'employee.order.cancel',

            'employee.payment_history',
            'profile.update'
        ]);

        Role::findByName('office_staff')->givePermissionTo([
            'dashboard',
            'office_staff.order.status',
            'profile.update'
        ]);


        $users = [
            [
                'name'  => 'Admin User',
                'email' => 'admin@gmail.com',
                'phone' => '01666666666',
                'role'  => 'admin',
            ],
            [
                'name'  => 'Employee User',
                'email' => 'employee@gmail.com',
                'phone' => '01777777777',
                'role'  => 'employee',
            ],
            [
                'name'  => 'Vendor User',
                'email' => 'vendor@gmail.com',
                'phone' => '01999999999',
                'role'  => 'vendor',
            ],
            [
                'name'  => 'Office Staff',
                'email' => 'office@gmail.com',
                'phone' => '01888888888',
                'role'  => 'office_staff',
            ],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name'     => $u['name'],
                    'phone'    => $u['phone'],
                    'password' => Hash::make('12345678'),
                ]
            );

            $user->syncRoles([$u['role']]);
        }
    }
}
