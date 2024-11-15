<?php

namespace App\Services;

class Auth
{
    public static function login($user)
    {
        if (is_array($user)) {
            unset($user['password']); 
            $_SESSION['user'] = $user['username'];
        } else {
            throw new \Exception("Expected user data as array, received something else.");
        }
    }

    public static function logout()
    {
        session_destroy();
    }

    public static function user(){
        return $_SESSION['user'];
    }
}