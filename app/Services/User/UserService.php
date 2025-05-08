<?php

namespace App\Services\User;

use App\Http\Filters\User\UserFilter;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Services\Xml\XmlResponse;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserService {

    public int $cacheTtl = 30; // cache time to live

    public function index(UserRequest $request) {
        $dataValidated = $request->validated();

        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $format = $request->query('format', 'json');

        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($dataValidated)]);

        $usersWithFilterAndSort = User::filter($filter)
            ->sort($dataValidated)
            ->paginate($perPage, ['*'], 'page', $page);

        $cacheKey = 'users.all.page.' . $page . '.per_page.' . $perPage . '.filter.' . json_encode($dataValidated);
        $users = Cache::remember($cacheKey, $this->cacheTtl, function () use ($usersWithFilterAndSort) {
            return $usersWithFilterAndSort;
        });

        $data = [
            'data' => $users->items(),
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => $users->lastPage(),
            'totalUsers' => $users->total()
        ];

        if ($format === 'xml') {
            return XmlResponse::arrayToXml($data);
        }

        return response()->json($data);
    }

    public function store(CreateUserRequest $request): JsonResponse {
        try {
            $validate = $request->validated();
            $validate['id'] = Str::uuid();
            $validate['password'] = bcrypt($validate['password']);

            $user = User::create($validate);
            $token = auth()->login($user);

            return response()->json([
                'user' => $validate,
                'token' => $token
            ], 201);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    public function show($id, Request $request) {
        $format = $request->query('format', 'json');
        $cacheKey = 'user.' . $id;

        $user = Cache::remember($cacheKey, $this->cacheTtl, function () use ($id) {
            return User::findOrFail($id);
        });

        if ($format === 'xml') {
            return XmlResponse::arrayToXml($user->toArray());
        }

        return response()->json($user);
    }

    public function update(UpdateRequest $request, $id): JsonResponse {
        $data = $request->validated();
        $user = User::findOrFail($id);
        $user->update($data);

        return response()->json($user->fresh());
    }

    public function trashed(): JsonResponse {
        $cacheKey = 'users.trashed';

        $trashedUsers = Cache::remember($cacheKey, $this->cacheTtl, function () {
            return User::onlyTrashed()->get();
        });

        return response()->json($trashedUsers);
    }

    public function handleDestroy($id, $force = false): JsonResponse {
        $user = User::withTrashed()->findOrFail($id);

        if ($force) {
            $user->forceDelete();
        } else {
            $user->delete();
        }

        return $this->messageResponse('User  deleted successfully!');
    }

    public function handleBulkDestroy($request, $force = false): JsonResponse {
        $userIds = $request->input('ids');
        $userIds = array_map('strval', $userIds);

        if (empty($userIds)) {
            return $this->messageResponse('You must specify at least one id!', 400);
        }

        if ($force) {
            User::whereIn('id', $userIds)->forceDelete();
        } else {
            User::whereIn('id', $userIds)->delete();
        }

        return $this->messageResponse('User deleted successfully!');
    }

    public function restore($id): JsonResponse {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json($user->fresh());
    }

    public function handleRestore($request, $id = null): JsonResponse {
        if ($id !== null) {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return response()->json($user->fresh());
        }

        if ($request->has('ids')) {
            $userIds = $request->input('ids');
            $userIds = array_map('strval', $userIds);

            if (empty($userIds)) {
                return $this->messageResponse('You must specify at least one id!', 400);
            }

            $restoredCount = User::onlyTrashed()->whereIn('id', $userIds)->restore();

            return $this->messageResponse("Users {$restoredCount} restored successfully!");
        }

        return $this->messageResponse('Users not restored!', 400);
    }

    private function handleQueryException(QueryException $e) {
        if ($e->errorInfo[1] === 1062) {
            return $this->messageResponse('Email already exists.', 409);
        }

        return $this->messageResponse('An error occurred while creating the user.', 500);
    }

    private function messageResponse($message, $code = 200) {
        return response()->json(['message' => $message], $code);
    }
}
