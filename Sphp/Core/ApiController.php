<?php

namespace Sphp\Core;


use App\Services\JwtAuthService;
use Sphp\Services\Auth;

class ApiController extends Controller
{
    private $rate_limiter = 1000;

    
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function errorResponse(string $message, int $statusCode = 400): void
    {
        $this->jsonResponse([
            'success' => false,
            'error' => $message,
        ], $statusCode);
    }

    protected function successResponse(string $message, array $data = [], int $statusCode = 200): void
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        $this->jsonResponse($response, $statusCode);
    }

  
    protected function setCorsHeaders(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Max-Age: 86400");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

   
    protected function getBearerToken(): ?string
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

   
    protected function authenticate(): void
    {
        $this->setCorsHeaders();

        $token = $this->getBearerToken();

        if (!$token) {
            $this->errorResponse("No token provided", 401);
        }

        $decodedUser = Auth::user($token);

        if (!$decodedUser) {
            try {
                $newTokens = Auth::refresh();
                $decodedUser = Auth::user($newTokens['access_token']);
                if ($decodedUser) {
                    $this->successResponse("Token refreshed", $newTokens, 200);
                    return;
                }
            } catch (\Exception $e) {
                $this->errorResponse("Invalid or expired token", 401);
            }
        }

        $this->user = $decodedUser;
    }

  
    
}