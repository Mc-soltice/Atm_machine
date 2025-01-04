<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        //******** créer des rôles */
     $roles=
        [
            'customer_user',
            'admin_user',
        ];
        foreach($roles as $role){
            
            Role::create(['name' => $role]);
        }
        
        // Créer des permissions
        $permissions=[
            'manage_user',
            'manage_account',
            'make_transaction'
        ];
        foreach($permissions as $permission){
            
            Permission::create(['name' => $permission]);
        }
        
        // Associer des permissions aux rôles
        $admin = Role::findByName('admin_user');
        $admin->givePermissionTo(['manage_user','manage_account']);
        

        // Associer des permissions aux rôles
        $Customer = Role::findByName('customer_user');
        $Customer->givePermissionTo(['make_transaction']);

    }
}
