<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory(10)->create();
        $this->createSuperAdmin();
    }

    protected function createSuperAdmin()
    {
        /** @var User $user */
        $user = User::factory()->createOne([
            'email' => 'test_super_admin@example.com',
            'password' => User::generateSecurePassword('RJWcO3fQQi05')
        ]);

        $user->assignRole(User::ROLE_SUPER_ADMIN);
    }
}
