<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use App\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    const ACTIVE = 1 ;


    public function all() {
        return Permission::all();
    }

    public function findByIdent($ident) {
        return Permission::where('ident', $ident)->where('active', self::ACTIVE)->first();

    }

    public function findByName($name) {
        return Permission::where('ident', $name)->where('active', self::ACTIVE)->first();
    }

    public function findById($id) {
        return Permission::findOrFail($id);
    }

    public function createOrUpdate($id = null, $collection = []) {

    }

    public function delete($id) {
        return Permission::findOrFail($id)->delete();
    }
}
