<?php

namespace App\Interfaces;

interface TenantRepositoryInterface
{
    public function createTenant($tenantId, $user);
    public function updateTenant($tenantId, $user);
    public function findById($id);
    public function createDomain($tenant, $tenantDomain);
}
