<?php

namespace App\Middleware;

use Sphp\Core\ApiController;
use Sphp\Core\Response;
use Sphp\Services\JwtAuthService;


class Api extends ApiController
{
    public function handle()
    {
        $this->setCorsHeaders();

        $token = $this->getBearerToken();

        if (!$token) {
            return $this->errorResponse("No access token provided", 401);
        }

        $authService = JwtAuthService::getInstance();

        $decodedUser = $authService->JwtValidate($token);

        // If access token is valid
        if ($decodedUser) {
            return $this->successResponse("Access token valid");
        }

        // If access token is expired or invalid, try to refresh it
        $refreshResponse = $authService->refreshToken();

        if ($refreshResponse['status'] === 200 && isset($refreshResponse['data']['access_token'])) {
            // Optionally set new access token in response headers
            header('X-New-Access-Token: ' . $refreshResponse['data']['access_token']);

            return $this->successResponse("Access token refreshed successfully",  [
                'access_token' => $refreshResponse['data']['access_token'], 200
            ]);
        }

        return $this->errorResponse("Invalid or expired token. Please login again.", 401);
    }
}
