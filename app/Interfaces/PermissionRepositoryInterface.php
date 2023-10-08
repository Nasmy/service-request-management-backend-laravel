<?php

namespace App\Interfaces;

interface PermissionRepositoryInterface
{
    public function all();
    public function findByIdent($ident);
    public function findByName($name);
    public function findById($id);
    public function createOrUpdate($id = null, $collection = []);
    public function delete($id);
}
