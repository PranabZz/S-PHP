<?php

namespace Sphp\Services;

use App\Models\Users;
use Exception;
use Sphp\Core\Database;
use App\Models\User;

class Auth
{
    private static $jwtAuthService;

    private static function initialize()
    {
        if (self::$jwtAuthService === null) {
            self::$jwtAuthService = JwtAuthService::getInstance();
        }
    }

    private static function startSecureSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_secure', 0);
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_samesite', 'Strict');
            session_start();
        }
    }

    public static function login($credentials)
    {
        self::initialize();
        self::startSecureSession();

        if (!is_array($credentials)) {
            throw new Exception("Expected user credentials as an array, received something else.");
        }

        $email = $credentials['email'] ?? null;
        $password = $credentials['password'] ?? null;

        if (!$email || !$password) {
            throw new Exception("Email and password are required for authentication.");
        }
        
        $user = new Users();

        $user = $user->select(['*'], ['email'=> $email])[0];
        
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password.");
        }

        $userId = $user['id'];
        $username = $user['name'];

        // Generate JWT tokens
        $tokens = self::$jwtAuthService->generateTokens($userId, $username);

        // Store tokens in session
        $_SESSION['user_token'] = $tokens['access_token'];
        $_SESSION['refresh_token'] = $tokens['refresh_token'];

        // Store tokens in secure cookies
        $accessTokenLifetime = 15 * 60;  // 15 minutes
        $refreshTokenLifetime = 7 * 24 * 60 * 60;  // 7 days

        setcookie('user_token', $tokens['access_token'], [
            'expires' => time() + $accessTokenLifetime,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        setcookie('refresh_token', $tokens['refresh_token'], [
            'expires' => time() + $refreshTokenLifetime,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        return [
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token']
        ];
    }



    public static function logout()
    {
        self::startSecureSession();

        // Unset session variables and destroy session
        session_unset();
        session_destroy();

        // Clear cookies
        setcookie('user_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        setcookie('refresh_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        return ['message' => 'Logged out successfully'];
    }

    public static function user($token = null)
    {
        self::initialize();
        self::startSecureSession();

        // Use provided token or fallback to session/cookie
        $accessToken = $token ?? $_SESSION['user_token'] ?? $_COOKIE['user_token'] ?? null;
        $refreshToken = $_SESSION['refresh_token'] ?? $_COOKIE['refresh_token'] ?? null;

        if (!$accessToken) {
            return null; // No user logged in
        }

        // Validate access token
        $decodedUser = self::$jwtAuthService->JwtValidate($accessToken);

        if (!$decodedUser && $refreshToken) {
            // Token is invalid or expired, attempt to refresh
            $newTokens = self::$jwtAuthService->refreshToken($refreshToken);

            if ($newTokens) {
                // Update session
                $_SESSION['user_token'] = $newTokens['access_token'];
                $_SESSION['refresh_token'] = $newTokens['refresh_token'];

                // Update cookies
                $accessTokenLifetime = 15 * 60;
                $refreshTokenLifetime = 7 * 24 * 60 * 60;

                setcookie('user_token', $newTokens['access_token'], [
                    'expires' => time() + $accessTokenLifetime,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                setcookie('refresh_token', $newTokens['refresh_token'], [
                    'expires' => time() + $refreshTokenLifetime,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                return self::$jwtAuthService->JwtValidate($newTokens['access_token']);
            } else {
                self::logout();
                return null;
            }
        }

        return $decodedUser;
    }

    public static function refresh()
    {
        self::initialize();
        self::startSecureSession();

        $refreshToken = $_SESSION['refresh_token'] ?? $_COOKIE['refresh_token'] ?? null;
        if (!$refreshToken) {
            throw new \Exception("No refresh token found.");
        }

        $newTokens = self::$jwtAuthService->refreshToken($refreshToken);
        if (!$newTokens) {
            throw new \Exception("Token refresh failed.");
        }

        // Update session
        $_SESSION['user_token'] = $newTokens['access_token'];
        $_SESSION['refresh_token'] = $newTokens['refresh_token'];

        // Update cookies
        $accessTokenLifetime = 15 * 60;
        $refreshTokenLifetime = 7 * 24 * 60 * 60;

        setcookie('user_token', $newTokens['access_token'], [
            'expires' => time() + $accessTokenLifetime,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        setcookie('refresh_token', $newTokens['refresh_token'], [
            'expires' => time() + $refreshTokenLifetime,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        return [
            'access_token' => $newTokens['access_token'],
            'refresh_token' => $newTokens['refresh_token']
        ];
    }

    public static function check($token = null): bool
    {
        self::initialize();
        self::startSecureSession();

        $accessToken = $token ?? $_SESSION['user_token'] ?? $_COOKIE['user_token'] ?? null;
        $refreshToken = $_SESSION['refresh_token'] ?? $_COOKIE['refresh_token'] ?? null;

        if (!$accessToken) {
            return false;
        }

        $decodedUser = self::$jwtAuthService->JwtValidate($accessToken);

        if (!$decodedUser && $refreshToken) {
            $newTokens = self::$jwtAuthService->refreshToken($refreshToken);

            if ($newTokens) {
                $_SESSION['user_token'] = $newTokens['access_token'];
                $_SESSION['refresh_token'] = $newTokens['refresh_token'];

                $accessTokenLifetime = 15 * 60;
                $refreshTokenLifetime = 7 * 24 * 60 * 60;

                setcookie('user_token', $newTokens['access_token'], [
                    'expires' => time() + $accessTokenLifetime,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                setcookie('refresh_token', $newTokens['refresh_token'], [
                    'expires' => time() + $refreshTokenLifetime,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                return (bool) self::$jwtAuthService->JwtValidate($newTokens['access_token']);
            }
            return false;
        }

        return (bool) $decodedUser;
    }
}