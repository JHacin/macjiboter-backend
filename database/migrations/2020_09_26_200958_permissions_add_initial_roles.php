<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class PermissionsAddInitialRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Role::create(['name' => User::ROLE_SUPER_ADMIN, 'label' => 'Super Administrator']);
        Role::create(['name' => User::ROLE_ADMIN, 'label' => 'Administrator']);
        Role::create(['name' => User::ROLE_EDITOR, 'label' => 'Urednik']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down(): void
    {
        Role::findByName(User::ROLE_SUPER_ADMIN)->delete();
        Role::findByName(User::ROLE_ADMIN)->delete();
        Role::findByName(User::ROLE_EDITOR)->delete();
    }
}
