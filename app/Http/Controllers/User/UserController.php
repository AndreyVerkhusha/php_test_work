<?php

namespace App\Http\Controllers\User;

use App\Http\Filters\UserFilter;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class UserController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index(Request $request) {
        return $this->userService->index($request);
    }

    public function store(CreateUserRequest $request) {
        return $this->userService->store($request);
    }

    public function show($id) {
        return $this->userService->show($id);
    }

    public function update(UpdateRequest $request, string $id) {
        return $this->userService->update($request, $id);
    }

    public function trashed() {
        return $this->userService->trashed();
    }


    public function destroy(string $id) {
        return $this->userService->handleDestroy($id);
    }

    public function forceDestroy(string $id) {
        return $this->userService->handleDestroy($id, true);
    }

    public function bulkDestroy(Request $request) {
        return $this->userService->handleBulkDestroy($request);
    }

    public function bulkForceDestroy(Request $request) {
        return $this->userService->handleBulkDestroy($request, true);
    }

    public function restore(Request $request, string $id) {
        return $this->userService->handleRestore($request, $id);
    }

    public function bulkRestore(Request $request) {
        return $this->userService->handleRestore($request);
    }
}
