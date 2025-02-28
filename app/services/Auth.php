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
        $_SESSION['refresh_token'] = $tokens['refresh_token']; // Store refresh token

        return [
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token']
        ];
    }

    public static function logout()
    {
        session_start();
        session_destroy();

        setcookie("refresh_token", "", time() - 3600, "/");

        return ['message' => 'Logged out successfully'];
    }

    public static function user()
    {
        session_start();
        self::initialize();

        $accessToken = $_SESSION['user_token'] ?? null;
        $refreshToken = $_SESSION['refresh_token'] ?? null;

        if (!$accessToken) {
            return null; // No user logged in
        }

        // Validate access token
        $decodedUser = self::$jwtAuthService->JwtValidate($accessToken);

        if (!$decodedUser) {
            // Token is invalid or expired, attempt to refresh
            if ($refreshToken) {
                $newTokens = self::$jwtAuthService->refreshToken($refreshToken);

                if ($newTokens) {
                    $_SESSION['user_token'] = $newTokens['access_token'];
                    $_SESSION['refresh_token'] = $newTokens['refresh_token'];

                    return self::$jwtAuthService->JwtValidate($newTokens['access_token']);
                } else {
                    session_destroy(); // Refresh failed, log out user
                    return null;
                }
            }
            return null; // No refresh token available
        }

        return $decodedUser;
    }

    public static function refresh()
    {
        self::initialize();
        session_start();

        $refreshToken = $_SESSION['refresh_token'] ?? null;
        if (!$refreshToken) {
            throw new \Exception("No refresh token found.");
        }

        $newTokens = self::$jwtAuthService->refreshToken($refreshToken);

        if (!$newTokens) {
            throw new \Exception("Token refresh failed.");
        }

        $_SESSION['user_token'] = $newTokens['access_token'];
        $_SESSION['refresh_token'] = $newTokens['refresh_token'];

        return [
            'access_token' => $newTokens['access_token'],
            'refresh_token' => $newTokens['refresh_token']
        ];
    }
}
