<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Products Group
            [
                'name' => 'View Products',
                'slug' => 'products.index',
                'description' => 'View list of products',
                'group' => 'Products',
            ],
            [
                'name' => 'Create Products',
                'slug' => 'products.create',
                'description' => 'Access product creation form',
                'group' => 'Products',
            ],
            [
                'name' => 'Store Products',
                'slug' => 'products.store',
                'description' => 'Create new products',
                'group' => 'Products',
            ],
            [
                'name' => 'View Product Details',
                'slug' => 'products.show',
                'description' => 'View individual product details',
                'group' => 'Products',
            ],
            [
                'name' => 'Edit Products',
                'slug' => 'products.edit',
                'description' => 'Access product edit form',
                'group' => 'Products',
            ],
            [
                'name' => 'Update Products',
                'slug' => 'products.update',
                'description' => 'Update existing products',
                'group' => 'Products',
            ],
            [
                'name' => 'Delete Products',
                'slug' => 'products.destroy',
                'description' => 'Delete products',
                'group' => 'Products',
            ],

            // Categories Group
            [
                'name' => 'View Categories',
                'slug' => 'categories.index',
                'description' => 'View list of categories',
                'group' => 'Categories',
            ],
            [
                'name' => 'Create Categories',
                'slug' => 'categories.create',
                'description' => 'Access category creation form',
                'group' => 'Categories',
            ],
            [
                'name' => 'Store Categories',
                'slug' => 'categories.store',
                'description' => 'Create new categories',
                'group' => 'Categories',
            ],
            [
                'name' => 'View Category Details',
                'slug' => 'categories.show',
                'description' => 'View individual category details',
                'group' => 'Categories',
            ],
            [
                'name' => 'Edit Categories',
                'slug' => 'categories.edit',
                'description' => 'Access category edit form',
                'group' => 'Categories',
            ],
            [
                'name' => 'Update Categories',
                'slug' => 'categories.update',
                'description' => 'Update existing categories',
                'group' => 'Categories',
            ],
            [
                'name' => 'Delete Categories',
                'slug' => 'categories.destroy',
                'description' => 'Delete categories',
                'group' => 'Categories',
            ],

            // Orders Group
            [
                'name' => 'View Orders',
                'slug' => 'orders.index',
                'description' => 'View list of orders',
                'group' => 'Orders',
            ],
            [
                'name' => 'View Order Details',
                'slug' => 'orders.show',
                'description' => 'View individual order details',
                'group' => 'Orders',
            ],
            [
                'name' => 'Update Orders',
                'slug' => 'orders.update',
                'description' => 'Update order status and details',
                'group' => 'Orders',
            ],

            // Admins Group
            [
                'name' => 'View Admins',
                'slug' => 'admins.index',
                'description' => 'View list of admin users',
                'group' => 'Admins',
            ],
            [
                'name' => 'Create Admins',
                'slug' => 'admins.create',
                'description' => 'Access admin creation form',
                'group' => 'Admins',
            ],
            [
                'name' => 'Store Admins',
                'slug' => 'admins.store',
                'description' => 'Create new admin users',
                'group' => 'Admins',
            ],
            [
                'name' => 'Edit Admins',
                'slug' => 'admins.edit',
                'description' => 'Access admin edit form',
                'group' => 'Admins',
            ],
            [
                'name' => 'Update Admins',
                'slug' => 'admins.update',
                'description' => 'Update existing admin users',
                'group' => 'Admins',
            ],
            [
                'name' => 'Delete Admins',
                'slug' => 'admins.destroy',
                'description' => 'Delete admin users',
                'group' => 'Admins',
            ],

            // Media Group
            [
                'name' => 'View Media',
                'slug' => 'media.index',
                'description' => 'View media library',
                'group' => 'Media',
            ],
            [
                'name' => 'Delete Media',
                'slug' => 'media.destroy',
                'description' => 'Delete media files',
                'group' => 'Media',
            ],
            [
                'name' => 'Access Media JSON',
                'slug' => 'media.json',
                'description' => 'Access media files as JSON',
                'group' => 'Media',
            ],

            // Customers Group
            [
                'name' => 'View Customers',
                'slug' => 'customers.index',
                'description' => 'View list of customers',
                'group' => 'Customers',
            ],
            [
                'name' => 'View Customer Details',
                'slug' => 'customers.show',
                'description' => 'View individual customer details',
                'group' => 'Customers',
            ],
            [
                'name' => 'Create Customers',
                'slug' => 'customers.create',
                'description' => 'Access customer creation form',
                'group' => 'Customers',
            ],
            [
                'name' => 'Store Customers',
                'slug' => 'customers.store',
                'description' => 'Create new customers',
                'group' => 'Customers',
            ],
            [
                'name' => 'Edit Customers',
                'slug' => 'customers.edit',
                'description' => 'Access customer edit form',
                'group' => 'Customers',
            ],
            [
                'name' => 'Update Customers',
                'slug' => 'customers.update',
                'description' => 'Update existing customers',
                'group' => 'Customers',
            ],
            [
                'name' => 'Delete Customers',
                'slug' => 'customers.destroy',
                'description' => 'Delete customers',
                'group' => 'Customers',
            ],

            // Drivers Group
            [
                'name' => 'View Drivers',
                'slug' => 'drivers.index',
                'description' => 'View list of drivers',
                'group' => 'Drivers',
            ],
            [
                'name' => 'View Driver Details',
                'slug' => 'drivers.show',
                'description' => 'View individual driver details',
                'group' => 'Drivers',
            ],
            [
                'name' => 'Create Drivers',
                'slug' => 'drivers.create',
                'description' => 'Access driver creation form',
                'group' => 'Drivers',
            ],
            [
                'name' => 'Store Drivers',
                'slug' => 'drivers.store',
                'description' => 'Create new drivers',
                'group' => 'Drivers',
            ],
            [
                'name' => 'Edit Drivers',
                'slug' => 'drivers.edit',
                'description' => 'Access driver edit form',
                'group' => 'Drivers',
            ],
            [
                'name' => 'Update Drivers',
                'slug' => 'drivers.update',
                'description' => 'Update existing drivers',
                'group' => 'Drivers',
            ],
            [
                'name' => 'Delete Drivers',
                'slug' => 'drivers.destroy',
                'description' => 'Delete drivers',
                'group' => 'Drivers',
            ],

            // Profile Group
            [
                'name' => 'Edit Profile',
                'slug' => 'profile.edit',
                'description' => 'Access profile edit form',
                'group' => 'Profile',
            ],
            [
                'name' => 'Update Profile',
                'slug' => 'profile.update',
                'description' => 'Update own profile',
                'group' => 'Profile',
            ],

            // Settings Group
            [
                'name' => 'View Settings',
                'slug' => 'settings.index',
                'description' => 'View system settings',
                'group' => 'Settings',
            ],
            [
                'name' => 'Update Settings',
                'slug' => 'settings.update',
                'description' => 'Update system settings',
                'group' => 'Settings',
            ],

            // Roles & Permissions Group
            [
                'name' => 'View Roles & Permissions',
                'slug' => 'roles-permissions.index',
                'description' => 'View roles and permissions management',
                'group' => 'Roles & Permissions',
            ],
            [
                'name' => 'Create Roles',
                'slug' => 'roles-permissions.storeRole',
                'description' => 'Create new roles',
                'group' => 'Roles & Permissions',
            ],
            [
                'name' => 'Update Roles',
                'slug' => 'roles-permissions.updateRole',
                'description' => 'Update existing roles',
                'group' => 'Roles & Permissions',
            ],
            [
                'name' => 'Delete Roles',
                'slug' => 'roles-permissions.destroyRole',
                'description' => 'Delete roles',
                'group' => 'Roles & Permissions',
            ],
            [
                'name' => 'Create Permissions',
                'slug' => 'roles-permissions.storePermission',
                'description' => 'Create new permissions',
                'group' => 'Roles & Permissions',
            ],
            [
                'name' => 'Update Permissions',
                'slug' => 'roles-permissions.updatePermission',
                'description' => 'Update existing permissions',
                'group' => 'Roles & Permissions',
            ],
            [
                'name' => 'Delete Permissions',
                'slug' => 'roles-permissions.destroyPermission',
                'description' => 'Delete permissions',
                'group' => 'Roles & Permissions',
            ],
            [
                'name' => 'Assign Permissions to Roles',
                'slug' => 'roles-permissions.updateRolePermissions',
                'description' => 'Assign permissions to roles',
                'group' => 'Roles & Permissions',
            ],

            // Audit Logs Group
            [
                'name' => 'View Audit Logs',
                'slug' => 'audit-logs.index',
                'description' => 'View system audit logs',
                'group' => 'Audit Logs',
            ],
            [
                'name' => 'View Audit Log Details',
                'slug' => 'audit-logs.show',
                'description' => 'View individual audit log details',
                'group' => 'Audit Logs',
            ],

            // Dashboard Group
            [
                'name' => 'View Dashboard',
                'slug' => 'dashboard',
                'description' => 'Access dashboard',
                'group' => 'Dashboard',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
