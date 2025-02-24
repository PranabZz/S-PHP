<?php

namespace App\Services;

class Auth
{
    private static $jwtAuthService;

    private static function initialize()
    {
        if (self::$jwtAuthService === null) {
            self::$jwtAuthService = JwtAuthService::getInstance();
        }
    }

    public static function login($user)
    {
        self::initialize();

        if (!is_array($user)) {
            throw new \Exception("Expected user data as array, received something else.");
        }

        $userId = $user['id'] ?? null;
        $username = $user['username'] ?? 'Guest';

        if (!$userId) {
            throw new \Exception("User ID is required for authentication.");
        }


        $tokens = self::$jwtAuthService->generateTokens($userId, $username);


        session_start();
        $_SESSION['user_token'] = $tokens['access_token'];

        return [
            'access_token' => $tokens['access_token']
        ];
    }

    public static function logout()
    {
        if (!empty($_COOKIE["refresh_token"])) {
            session_start();
            session_destroy();


            setcookie("refresh_token", "", time() - 3600, "/");

            return ['message' => 'Logged out successfully'];
        } else {
            throw new \Exception("Cannot logout until user is logged in.");
        }
    }

    public static function user()
    {
        session_start();
        $accessToken = $_SESSION['user_token'] ?? null;

        if (!$accessToken) {
            return null; // No user logged in
        }

        self::initialize();

        $decodedUser = self::$jwtAuthService->JwtValidate($accessToken);

        return $decodedUser ?: null;
    }

    public static function refresh()
    {
        self::initialize();
        return self::$jwtAuthService->refreshToken();
    }
}
