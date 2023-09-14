<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Tests\Database\Seeders;

use AMgrade\LaravelRoles\Models\Permission;
use AMgrade\LaravelRoles\Models\Role;
use AMgrade\LaravelRoles\Tests\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $i = 0;

        Schema::disableForeignKeyConstraints();

        foreach (Config::get('roles.tables') as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        foreach ($this->getData() as $role => $permissions) {
            /** @var \AMgrade\LaravelRoles\Models\Role $roleModel */
            $roleModel = Role::query()->create([
                'name' => $role,
                'level' => ++$i,
            ]);

            foreach ($permissions as $permission) {
                /** @var \AMgrade\LaravelRoles\Models\Permission $permissionModel */
                $permissionModel = Permission::query()->create([
                    'name' => $permission,
                ]);

                $roleModel->attachPermission($permissionModel);
            }

            /** @var \AMgrade\LaravelRoles\Tests\Models\User $user */
            $user = User::query()->create([
                'name' => $role,
                'email' => "{$role}@example.com",
                'email_verified_at' => Carbon::now(),
                'password' => 'pass',
            ]);

            $user->attachRole($roleModel);
        }
    }

    protected function getData(): array
    {
        return [
            'editor' => [
                'blog.create',
                'blog.update',
                'blog.delete',
            ],
            'moderator' => [
                'comment.create',
                'comment.update',
                'comment.delete',
                'comment.approve',
                'comment.disapprove',
            ],
            'admin' => [
                'settings.create',
                'settings.update',
            ],
        ];
    }
}
