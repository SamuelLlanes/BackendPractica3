<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index(Request $request)
    {
    if ($request->boolean('trashed')) {
        $users = User::onlyTrashed()->get();
    } else {
        $users = User::all();
    }

    return UserResource::collection($users);
    }

    public function restore($id)
    {
    $user = User::onlyTrashed()->find($id);

    if (!$user) {
        return response()->json([
            'message' => 'Usuario no encontrado.'
        ], 404);
    }

    $user->restore();

    return response()->json([
        'message' => 'Usuario restaurado correctamente.'
    ]);
    }
    public function show($id)
    {
    $user = User::findOrFail($id);

    return new UserResource($user);
    }

    public function destroy($id)
    {
    $user = User::findOrFail($id);

    $user->delete();

    return response()->json([
        'message' => 'El usuario ha sido eliminado correctamente.'
    ], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
    $user = User::findOrFail($id);

    $data = $request->validated();
    if (isset($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    }

    $user->update($data);

    return response()->json(
        new UserResource($user),
        200
    );
    }
    
    public function store(StoreUserRequest $request)
    {
    $data = $request->validated();

    $plain = Str::random(8);
    $data['password'] = Hash::make($plain);
    $data['hiring_date'] = $data['hiring_date'] ?? now();

    $user = User::create($data);

    return response()->json(
        new UserResource($user),
        201
    );
    }
}