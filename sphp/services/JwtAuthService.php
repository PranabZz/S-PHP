<?php

namespace Sphp\Services;

use Exception;
use Sphp\Core\Response;
use Sphp\Core\Database;

class JwtAuthService
{
    private $jwt_secret = "pranab_key";
    private $header = ["alg" => "HS256", "type" => "JWT"];
    private static $instance = null;
    private $accessTokenExpiry = 30; // 15 minutes
    private $refreshTokenExpiry = 604800; // 7 days
    private $db;

    // Private constructor to prevent multiple instances
    private function __construct()
    {
        // Initialize database connection (adjust config as needed)
        $dbConfig = [
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD')
        ];

        $this->db = new Database($dbConfig);
    }

    // Singleton instance getter
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new JwtAuthService();
        }
        return self::$instance;
    }

    // Generate both access and refresh tokens
    public function generateTokens($userId, $name)
    {
        $accessTokenPayload = [
            'id' => $userId,
            'name' => $name,
            'exp' => time() + $this->accessTokenExpiry
        ];

        $refreshTokenPayload = [
            'id' => $userId,
            'exp' => time() + $this->refreshTokenExpiry
        ];

        $accessToken = $this->JwtEncrypt($accessTokenPayload);
        $refreshToken = $this->JwtEncrypt($refreshTokenPayload);

        // Store refresh token in database (hashed)
        $this->storeRefreshTokenInDatabase($userId, $refreshToken);

        // Store refresh token in an HTTP-only cookie
        $this->storeJwtInCookie($refreshToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    // Encrypt the JWT (header + payload + signature)
    public function JwtEncrypt($payload)
    {
        $encodedHeader = rtrim(strtr(base64_encode(json_encode($this->header)), '+/', '-_'), '=');
        $encodedPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
        $dataToSign = $encodedHeader . "." . $encodedPayload;
        $signature = hash_hmac('sha256', $dataToSign, $this->jwt_secret, true);
        $encodedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
        return $encodedHeader . "." . $encodedPayload . "." . $encodedSignature;
    }

    // Refresh the JWT using the refresh token
    public function refreshToken()
    {
        $refreshToken = $this->getJwtFromCookie();

        if (!$refreshToken) {
            return Response::response(401, 'No refresh token found');
        }

        // Validate refresh token (JWT structure and expiration)
        $decodedRefreshToken = $this->JwtValidate($refreshToken);

        if (!$decodedRefreshToken) {
            return Response::response(401, 'Invalid or expired refresh token');
        }

        $userId = $decodedRefreshToken['id'];

        // Verify refresh token exists in database
        if (!$this->verifyRefreshTokenInDatabase($userId, $refreshToken)) {
            return Response::response(401, 'Invalid refresh token');
        }

        // Generate new tokens
        $newTokens = $this->generateTokens($userId, "User_" . $userId);

        return Response::response(200, $newTokens, 'new_tokens');
    }

    // Validate the JWT (check signature and expiration)
    public function JwtValidate($token)
    {
        try {
            list($encodedHeader, $encodedPayload, $signature) = explode('.', $token);
            $decodedHeader = json_decode(base64_decode(strtr($encodedHeader, '-_', '+/')), true);
            $decodedPayload = json_decode(base64_decode(strtr($encodedPayload, '-_', '+/')), true);

            // Check expiration
            if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < time()) {
                return false;
            }

            $headerAndPayload = $encodedHeader . '.' . $encodedPayload;
            $generatedSignature = hash_hmac('sha256', $headerAndPayload, $this->jwt_secret, true);
            $decodedSignature = base64_decode(strtr($signature, '-_', '+/'));

            if (hash_equals($generatedSignature, $decodedSignature)) {
                return $decodedPayload;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Regenerate a new JWT based on the old one
    public function regenerateJwt($oldJwt)
    {
        $decodedOldJwt = $this->JwtValidate($oldJwt);

        if (!$decodedOldJwt) {
            return Response::response(401, 'Error while generating new token');
        }

        $userId = $decodedOldJwt['id'];

        $newPayload = [
            'id' => $userId,
            'name' => $decodedOldJwt['name'],
            'exp' => time() + $this->accessTokenExpiry
        ];

        $newJwt = $this->JwtEncrypt($newPayload);

        return Response::response(200, $newJwt, 'new_jwt');
    }

    // Store JWT in HTTP-only cookie
    public function storeJwtInCookie($jwt)
    {
        $cookieName = "user_token";
        $cookieValue = $jwt;
        $expireTime = time() + $this->refreshTokenExpiry;
        $path = "/";
        $secure = true; // Ensure HTTPS in production
        $httpOnly = true;
        $sameSite = "None";
        $domain = ".localhost.com";

        setcookie(
            $cookieName,
            $cookieValue,
            [
                'expires' => $expireTime,
                'path' => $path,
                'secure' => $secure,
                'httponly' => $httpOnly,
                'domain' => $domain,
                'samesite' => $sameSite,
            ]
        );
    }

    // Get JWT from cookie
    public function getJwtFromCookie()
    {
        return isset($_COOKIE['user_token']) ? $_COOKIE['user_token'] : null;
    }

    // Store refresh token in database (hashed)
    private function storeRefreshTokenInDatabase($userId, $refreshToken)
    {
        try {
            $tokenHash = password_hash($refreshToken, PASSWORD_BCRYPT);
            $expiresAt = date('Y-m-d H:i:s', time() + $this->refreshTokenExpiry);

            // First, delete any existing refresh tokens for the user
            $deleteQuery = "DELETE FROM refresh_tokens WHERE user_id = ?";
            $this->db->query($deleteQuery, [$userId]);

            // Now insert the new refresh token
            $insertQuery = "INSERT INTO refresh_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)";
            $this->db->query($insertQuery, [$userId, $tokenHash, $expiresAt]);

        } catch (Exception $e) {
            // Use proper error handling/logging
            dd("Error storing refresh token: " . $e->getMessage());
        }
    }


    // Verify refresh token in database
    private function verifyRefreshTokenInDatabase($userId, $refreshToken)
    {
        // Fetch the stored token hash
        $result = $this->db->query("
            SELECT token_hash, expires_at
            FROM refresh_tokens
            WHERE user_id = ? AND expires_at > NOW()
        ", [$userId]);

        if (!$result) {
            return false;
        }

        // Verify the token against the stored hash
        return password_verify($refreshToken, $result['token_hash']);
    }

    // Optional: Revoke a refresh token (e.g., on logout)
    public function revokeRefreshToken($userId)
    {
        $stmt = $this->db->query("DELETE FROM refresh_tokens WHERE user_id = ?", [$userId]);
        // Also clear the cookie
        setcookie('user_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'domain' => '.localhost.com',
            'samesite' => 'None'
        ]);
        return Response::response(200, 'Refresh token revoked');
    }
}