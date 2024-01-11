<?php

namespace Database\Seeders;

use App\Modules\Auth\Repositories\Elasticsearch\AccountRepository;
use App\Modules\Auth\Repositories\Elasticsearch\PermissionRepository;
use App\Modules\Auth\Repositories\Elasticsearch\RoleRepository;
use Carbon\Carbon;
use Common\app\Indexes\AccountIndex;
use Common\App\Indexes\PermissionIndex;
use Common\App\Indexes\RoleIndex;
use Common\App\Models\Account;
use Common\App\Models\Permission;
use Common\App\Models\Role;
use Hash;
use Illuminate\Database\Seeder;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Permissions
        $permissionData = [
            ['name' => 'list-endpoint'],
            ['name' => 'create-endpoint'],
            ['name' => 'edit-endpoint'],
            ['name' => 'delete-endpoint'],

            ['name' => 'list-service'],
            ['name' => 'create-service'],
            ['name' => 'edit-service'],
            ['name' => 'delete-service'],
        ];

        foreach ($permissionData as $item) {
            Permission::query()->create($item);
        }

        $permissions = Permission::all();
        $permissionRepository = new PermissionRepository(new PermissionIndex());
        foreach ($permissions as $permission) {
            $permissionRepository->create($permission->getAttributes());
        }

        // Create Super Admin Roles
        $role = Role::query()->create([
            'name' => 'super-admin',
        ]);

        $permissions = Permission::all();

        $role->permissions()->sync($permissions->pluck('id'));

        $roleRepository = new RoleRepository(new RoleIndex());
        $roleRepository->create([
            'id' => $role->getAttribute('id'),
            'name' => $role->getAttribute('name'),
            'permissions' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                ];
            })->toArray(),
            'created_at' => Carbon::parse($role->getAttribute('created_at'))->timestamp,
            'updated_at' => Carbon::parse($role->getAttribute('updated_at'))->timestamp,
        ]);

        // Create Super Admin Account
        $account = Account::query()->create([
            'guid' => Str::uuid()->getHex()->toString(),
            'email' => 'hoainam@tuoitre.com.vn',
            'password_hash' => Hash::make('123123123a'),
        ]);

        $roles = Role::all();

        $account->roles()->sync($roles->pluck(['id']));

        $accountRepository = new AccountRepository(new AccountIndex());
        $accountRepository->create([
            'id' => $account->getAttribute('id'),
            'guid' => $account->getAttribute('guid'),
            'email' => $account->getAttribute('email'),
            'password_hash' => $account->getAttribute('password_hash'),
            'roles' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
            'created_at' => Carbon::parse($role->getAttribute('created_at'))->timestamp,
            'updated_at' => Carbon::parse($role->getAttribute('updated_at'))->timestamp,
        ]);
    }
}
