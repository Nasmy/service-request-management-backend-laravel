<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserPasswordUpdateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\TenantService;

class UserController extends Controller
{
    public UserRepository $userRepository;
    public TenantService $tenantService;

    public function __construct(UserRepository $userRepository, TenantService $tenantService)
    {
        $this->userRepository = $userRepository;
        $this->tenantService = $tenantService;
    }

    public function index()
    {
        return view('users.create');
    }

    public function edit($id)
    {
        $user = $this->userRepository->findById($id);
        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $this->userRepository->update($id, $request->validated());
        return redirect()->route('dashboard.index')->with('success', 'User detail updated is successfully');
    }

    // GameController.php

    public function destroy($id)
    {
        return $this->userRepository->deleteTenantUser($id);
    }

    public function editPassword(User $user)
    {
        return view('users.edit-password', ['user' => $user]);
    }

    public function updatePassword(UserPasswordUpdateRequest $request, User $user)
    {
        try {
            $this->tenantService->updateAdminPassword($user->id, $request->validated());
            return redirect()->route('dashboard.index');
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
