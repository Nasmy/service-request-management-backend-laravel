<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $search = $request->all();
        $search['role_id'] = Role::DEFAULT_TENANT_ROLE_ID;

        $tenants = $this->userRepository->search($search);

        return view('dashboard.main', ['tenants' => $tenants]);
    }
}
