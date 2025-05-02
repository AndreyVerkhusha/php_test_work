<?php

namespace App\Services\User;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserService {

    public function index(Request $request) {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $users = User::paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $users->items(),
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => $users->lastPage(),
            'totalUsers' => $users->total()
        ]);
    }

    public function store(CreateUserRequest $request) {
        try {
            $validate = $request->validated();
            $validate['id'] = Str::uuid();
            $validate['password'] = bcrypt($validate['password']);

            $user = User::create($validate);
            $token = auth()->login($user);

            return response()->json([
                'message' => 'User  created successfully!',
                'user' => $validate,
                'token' => $token
            ], 201);
        } catch (QueryException $e) {

            return $this->handleQueryException($e);
        }
    }

    public function show($id) {
        return response()->json(User::findOrFail($id));
    }

    public function update(UpdateRequest $request, $id) {
        $data = $request->validated();
        $user = User::findOrFail($id);
        $user->update($data);

        return response()->json($user->fresh());
    }

    public function trashed() {
        $trashedUsers = User::onlyTrashed()->get();
        return response()->json($trashedUsers);
    }

    public function handleDestroy($id, $force = false) {
        $user = User::withTrashed()->findOrFail($id);

        if ($force) {
            $user->forceDelete();
        } else {
            $user->delete();
        }

        return $this->messageResponse('User  deleted successfully!');
    }

    public function handleBulkDestroy($request, $force = false) {
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

    public function restore($id) {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json($user->fresh());
    }

    public function handleRestore($request, $id = null) {
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
