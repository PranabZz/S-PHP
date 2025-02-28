<?php

namespace App\Services;
use App\Core\Response;

class JwtAuthService
{
    private $jwt_secret = "pranab_key";
    private $header = ["alg" => "HS256", "type" => "JWT"];
    private static $instance = null;
    private $accessTokenExpiry = 1; // 15 minutes
    private $refreshTokenExpiry = 604800; // 7 days

    // Private constructor to prevent multiple instances
    private function __construct() {}

    // Singleton instance getter
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new JwtAuthService();
        }
        return self::$instance;
    }

    public function generateTokens($userId, $username)
    {
        $accessTokenPayload = [
            'id' => $userId,
            'username' => $username,
            'exp' => time() + $this->accessTokenExpiry
        ];

        $refreshTokenPayload = [
            'id' => $userId,
            'exp' => time() + $this->refreshTokenExpiry
        ];

        $accessToken = $this->JwtEncrypt($accessTokenPayload);
        $refreshToken = $this->JwtEncrypt($refreshTokenPayload);

        // Store refresh token in an HTTP-only cookie
        $this->storeJwtInCookie($refreshToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken // Not sent to the frontend, just stored in cookies
        ];
    }
    public function JwtEncrypt($payload)
    {
        $encodedHeader = rtrim(strtr(base64_encode(json_encode($this->header)), '+/', '-_'), '=');
        $encodedPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

        $dataToSign = $encodedHeader . "." . $encodedPayload;

        $signature = hash_hmac('sha256', $dataToSign, $this->jwt_secret, true); // Raw binary output

        $encodedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $jwt = $encodedHeader . "." . $encodedPayload . "." . $encodedSignature;

        $this->storeJwtInCookie($jwt);

        return true;
    }

    public function refreshToken()
    {
        $refreshToken = $this->getJwtFromCookie();

        if (!$refreshToken) {
            return Response::response(401, 'No refresh token found');
        }

        $decodedRefreshToken = $this->JwtValidate($refreshToken);

        if (!$decodedRefreshToken) {
            return Response::response(401, 'Invalid or expired refresh token');
        }

        $userId = $decodedRefreshToken['id'];

        $newTokens = $this->generateTokens($userId, "User_" . $userId); // Adjust as needed

        return Response::response(200, $newTokens, 'new_tokens');
    }

    public function JwtValidate($token)
    {
        $jwt = $this->getJwtFromCookie();

        list($encodedHeader, $encodedPayload, $signature) = explode('.', $jwt);

        $decodedHeader = json_decode(base64_decode(strtr($encodedHeader, '-_', '+/')), true);
        $decodedPayload = json_decode(base64_decode(strtr($encodedPayload, '-_', '+/')), true);

        $headerAndPayload = $encodedHeader . '.' . $encodedPayload;

        $generatedSignature = hash_hmac('sha256', $headerAndPayload, $this->jwt_secret, true); // Raw binary output

        $decodedSignature = base64_decode(strtr($signature, '-_', '+/'));

        if (hash_equals($generatedSignature, $decodedSignature)) {
            return $decodedPayload; 
        } else {
            return false;
        }
    }

    public function regenerateJwt($oldJwt)
    {
        $decodedOldJwt = $this->JwtValidate($oldJwt);

        if (!$decodedOldJwt) {
            return Response::response(401, 'Error while generating new token' );
        }

        $userId = $decodedOldJwt['id'];  

        $newPayload = [
            'id' => $userId, // You can add more data if needed
            'username' => $decodedOldJwt['username'], // Example of adding more data
        ];

        $newJwt = $this->JwtEncrypt($newPayload);

        return Response::response(200, $newJwt, 'new_jwt');
    }


    public function storeJwtInCookie($jwt)
    {
        $cookieName = "user_token";
        $cookieValue = $jwt;
        $expireTime = time() + 3600; // 1 hour from now
        $path = "/";
        $secure = false; // For Testing False 
        $httpOnly = true;
        $sameSite = "Strict";

        setcookie(
            $cookieName,
            $cookieValue,
            [
                'expires' => time() + $this->refreshTokenExpiry,
                'path' => $path,
                'secure' => $secure,
                'httponly' => $httpOnly,
                // 'samesite' => $sameSite
            ]
        );
    }

    public function getJwtFromCookie()
    {
        if (isset($_COOKIE['user_token'])) {
            return $_COOKIE['user_token'];
        } else {
            return null;
        }
    }


}
