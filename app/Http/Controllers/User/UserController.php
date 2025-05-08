<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\UserRequest;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class UserController extends Controller {
    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index(UserRequest $request): JsonResponse {
        return $this->userService->index($request);
    }

    public function store(CreateUserRequest $request): JsonResponse {
        return $this->userService->store($request);
    }

    public function show(string $id, Request $request): JsonResponse {
    return $this->userService->show($id, $request);
}

    public function update(UpdateRequest $request, string $id): JsonResponse {
    return $this->userService->update($request, $id);
}

    public function trashed(): JsonResponse {
    return $this->userService->trashed();
}

    public function destroy(string $id): JsonResponse {
    return $this->userService->handleDestroy($id);
}

    public function forceDestroy(string $id): JsonResponse {
    return $this->userService->handleDestroy($id, true);
}

    public function bulkDestroy(Request $request): JsonResponse {
    return $this->userService->handleBulkDestroy($request);
}

    public function bulkForceDestroy(Request $request): JsonResponse {
    return $this->userService->handleBulkDestroy($request, true);
}

    public function restore(Request $request, string $id): JsonResponse {
    return $this->userService->handleRestore($request, $id);
}

    public function bulkRestore(Request $request): JsonResponse {
    return $this->userService->handleRestore($request);
}
}
