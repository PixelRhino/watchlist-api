<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'register']]);
  }

  public function login(): JsonResponse
  {
    $credentials = request(['email', 'password']);

    if (! $token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
  }

  public function register(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      "name" => ["required", "string"],
      "email" => ["required", "email", "unique:users"],
      "password" => ["required", "string", "min:8"]
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 422);
    }

    $user = User::query()->create(array_merge(
      $validator->validated(),
      ["password" => bcrypt($request->get('password'))]
    ));

    if(!$user){
      return response()->json(['error' => 'User could not be created'], 422);
    }

    $credentials = request(['email', 'password']);

    if (! $token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
  }

  public function me(): JsonResponse
  {
    return response()->json(auth()->user());
  }

  public function logout(): JsonResponse
  {
    auth()->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  public function refresh(): JsonResponse
  {
    return $this->respondWithToken(auth()->refresh());
  }

  protected function respondWithToken($token): JsonResponse
  {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ]);
  }

}
