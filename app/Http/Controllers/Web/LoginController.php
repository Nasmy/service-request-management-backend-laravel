<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function authenticate(LoginFormRequest $request)
    {
        try {
            $request->validated();
            return $this->authService->webUserLogin(['username' => $request->input('username'), 'password' => $request->input('password')]);
        } catch (\Exception $exception) {

        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('auth.logout');
    }
}
