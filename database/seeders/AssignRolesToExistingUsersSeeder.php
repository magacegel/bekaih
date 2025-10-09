<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesToExistingUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users that have a level column
        $users = User::whereNotNull('level')->get();

        foreach ($users as $user) {
            // Check if role exists
            $roleExists = Role::where('name', $user->level)->exists();
            
            if ($roleExists) {
                // Remove all existing roles first
                $user->syncRoles([]);
                
                // Assign the new role based on their level
                $user->assignRole($user->level);
                
                echo "Assigned role '{$user->level}' to user: {$user->name} (ID: {$user->id})\n";
            } else {
                echo "Role '{$user->level}' does not exist for user: {$user->name} (ID: {$user->id})\n";
            }
        }
    }
}
