<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTenantAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $database = $this->tenant->database()->getName();
        if (!$this->tenant->database()->manager()->databaseExists($database)) {
            return;
        }

        $this->tenant->run(function ($tenant) {
            $update = [
                'first_name' => $tenant->first_name,
                'last_name' => $tenant->last_name,
                'mobile' => $tenant->mobile,
                'email' => $tenant->email,
                'username' => $tenant->username,
                'city' => $tenant->city,
                'zip' => $tenant->zip,
                'address' => $tenant->address,
                'password' => $tenant->password,
            ];

            // Perform update within given tenant scope
            User::where(['username' => $tenant->username])->update($update);
        });
    }
}
