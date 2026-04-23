<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserFlowSeeder extends Seeder
{
    /**
     * Crea (si no existe) el usuario administrador y le asigna el rol `super_admin`.
     *
     * Variables opcionales:
     * - `SUPER_ADMIN_EMAIL`
     * - `SUPER_ADMIN_PASSWORD`
     */
    public function run(): void
    {
        $email = (string) env('SUPER_ADMIN_EMAIL', 'hola@maxquispe.com');
        $password = (string) env('SUPER_ADMIN_PASSWORD', 'Nickmax1730*#');

        $role = Role::query()->firstOrCreate(['name' => 'super_admin']);

        /** @var User $superAdmin */
        $superAdmin = User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ],
        );

        if (! $superAdmin->hasRole($role)) {
            $superAdmin->assignRole($role);
        }

        $this->command?->info("Super Admin listo: {$superAdmin->email}");
    }
}
