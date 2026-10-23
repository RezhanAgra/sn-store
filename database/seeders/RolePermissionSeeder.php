<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Modul admin dan aksi yang tersedia di tiap modul.
     *
     * @var array<string, array<int, string>>
     */
    private const MODULES = [
        'dashboard' => ['view'],
        'categories' => ['view', 'create', 'edit', 'delete'],
        'products' => ['view', 'create', 'edit', 'delete'],
        'transactions' => ['view', 'create', 'edit', 'delete'],
        'users' => ['view', 'create', 'edit', 'delete'],
    ];

    /**
     * Buat permission (format "modul.aksi") dan dua role: admin (semua permission) dan customer (tanpa permission).
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(self::MODULES)
            ->flatMap(fn (array $actions, string $module): array => array_map(
                fn (string $action): string => "{$module}.{$action}",
                $actions,
            ))
            ->map(fn (string $name): Permission => Permission::findOrCreate($name, 'web'));

        Role::findOrCreate('admin', 'web')->syncPermissions($permissions);

        Role::findOrCreate('customer', 'web')->syncPermissions([]);

        Role::whereNotIn('name', ['admin', 'customer'])->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
