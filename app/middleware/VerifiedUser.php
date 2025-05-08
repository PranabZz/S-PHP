<?php

namespace App\Middleware;

use Sphp\Core\Response;
use App\Models\User;
use Sphp\Services\Auth;

class VerifiedUser
{
    public function handle()
    {
        if (!Auth::check()) {
            $_SESSION['error'] = 'Please login to continue.';
            redirect('/login');
            exit;
        }
    
        $user_id = Auth::user()['id'];
        $userModel = new User();
        $isVerified = $userModel->getVerifyByid($user_id); // Expected to return true if verified, false otherwise
    
        if ($isVerified) {
            return true;
        }
    
        $_SESSION['error'] = 'Please verify your email address.';
        redirect('/verify');
        exit;
    }
    
}
