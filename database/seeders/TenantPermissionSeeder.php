<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Database\Seeder;

class TenantPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionList = PermissionService::getPermissions();

        foreach ($permissionList as $permission) {
            $name = $permission['name'];
            $parent = $permission['parent'];
            $description = $permission['description'];
            $active = $permission['active'];
            foreach ($permission['child'] as $child) {
                $ident = $parent . "." . $child;
                $this->createRecord($name, $parent, $child, $ident, $description, $active);
            }
        }
    }

    private function createRecord(string $name, string $parent, string $child, string $ident, string $description, bool $active)
    {
        Permission::firstOrCreate([
            'name' => $name,
            'parent' => $parent,
            'child' => $child,
            'ident' => $ident,
            'description' => $description,
            'active' => $active
        ]);
    }
}
