<?php

namespace App\Providers;

use App\Interfaces\CrudModelRepository;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\RolePermissionRepositoryInterface;
use App\Interfaces\TenantRepositoryInterface;
use App\Repositories\OrganizationRepository;
use App\Repositories\CitizenRepository;
use App\Repositories\JobRepository;
use App\Repositories\ContactRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(CrudModelRepository::class, RoleRepository::class);
        $this->app->bind(RolePermissionRepositoryInterface::class, RolePermissionRepository::class);
        $this->app->bind(CrudModelRepository::class, UserRepository::class);
        $this->app->bind(TenantRepositoryInterface::class, TenantRepository::class);
        $this->app->bind(CrudModelRepository::class, OrganizationRepository::class);
        $this->app->bind(CrudModelRepository::class, ContactRepository::class);
        $this->app->bind(CrudModelRepository::class, CitizenRepository::class);
        $this->app->bind(CrudModelRepository::class, JobRepository::class);
        $this->app->bind(CrudModelRepository::class, DocumentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
