<?php

declare(strict_types = 1);

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use McMatters\LaravelRoles\Models\Permission;
use McMatters\LaravelRoles\Models\Role;
use McMatters\LaravelRoles\Tests\Models\User;

/**
 * Class UserRolePermissionSeeder
 */
class UserRolePermissionSeeder extends Seeder
{
    /**
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function run(): void
    {
        $i = 0;

        Schema::disableForeignKeyConstraints();

        foreach (Config::get('roles.tables') as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        foreach ($this->getData() as $role => $permissions) {
            /** @var \McMatters\LaravelRoles\Models\Role $roleModel */
            $roleModel = Role::query()->create([
                'name' => $role,
                'level' => ++$i,
            ]);

            foreach ($permissions as $permission) {
                /** @var \McMatters\LaravelRoles\Models\Permission $permissionModel */
                $permissionModel = Permission::query()->create([
                    'name' => $permission,
                ]);

                $roleModel->attachPermission($permissionModel);
            }

            /** @var \McMatters\LaravelRoles\Tests\Models\User $user */
            $user = User::query()->create([
                'name' => $role,
                'email' => "{$role}@example.com",
                'email_verified_at' => Carbon::now(),
                'password' => 'pass',
            ]);

            $user->attachRole($roleModel);
        }
    }

    /**
     * @return array
     */
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
