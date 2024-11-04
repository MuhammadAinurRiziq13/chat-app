<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['username'] = strstr($data['email'], '@', true);

        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 'User berhasil melakukan register');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $isValid = $this->isValidCredentials($request);

        if (!$isValid['success']) {
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $isValid['user'];
        $token = $user->createToken(User::USER_TOKEN);
        return $this->success([
            'user' => $user,
            $token => $token->plainTextToken
        ], "Login Berhasil");
    }

    private function isValidCredentials(LoginRequest $request): array
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if ($user == null) {
            return [
                "success" => false,
                "message" => "Email belum terdaftar"
            ];
        }

        if (Hash::check($data['password'], $user->password)) {
            return [
                "success" => true,
                "user" => $user
            ];
        }

        return [
            "success" => false,
            "message" => "password tidak sesuai"
        ];
    }
    public function loginWithToken()
    {
        return $this->success(Auth::user(), "Login Berhasil");
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, "Logout Berhasil");
    }
}