<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * To login with email and password and generate API Access Token
     * @author Htoo Maung Thait (htoomaungthait@gmail.com)
     * @since 2026-09-30
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $token = "";

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
                'error'   => 'INVALID_CREDENTIALS',
            ], 401);
        }

        $payload = JWTAuth::setToken($token)->getPayload();

        $expiresAt = $payload->get('exp');

        return response()->json([
            'success'              => true,
            'message'              => 'Login successful.',
            'access_token'         => $token,
            'token_type'           => 'Bearer',
            'expires_at'           => Carbon::createFromTimestamp(
                $expiresAt,
                'Asia/Yangon'
            )->toIso8601String(),
            'expires_at_timestamp' => $expiresAt,
        ]);

    }

    /**
     * After login to logout disabling the Access Token
     * @author Htoo Maung Thait (htoomaungthait@gmail.com)
     * @since 2026-09-30
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        Auth::guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }

}
