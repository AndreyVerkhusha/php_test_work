<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;

class AuthController extends Controller {
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function login(FormRequest $request) {
        return $this->authService->login($request);
    }

    public function logout() {
        return $this->authService->logout();
    }

    public function me() {
        return $this->authService->me();
    }
}
