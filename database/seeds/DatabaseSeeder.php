<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@portal.dev',
            'password' => Hash::make('admin'),
            'profile_id' => -1,
            'profile_completed' => 0,
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@portal.dev',
            'password' => Hash::make('portal'),
            'profile_id' => -1,
            'profile_completed' => 0,
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@portal.dev',
            'password' => Hash::make('portal'),
            'profile_id' => -1,
            'profile_completed' => 0,
        ]);
        $user = User::create([
            'name' => 'User',
            'email' => 'user@portal.dev',
            'password' => Hash::make('portal'),
            'profile_id' => -1,
            'profile_completed' => 0,
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@portal.dev',
            'password' => Hash::make('portal'),
            'profile_id' => -1,
            'profile_completed' => 0,
        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);
        $this->call(UsersTableSeeder::class);
    }
}
