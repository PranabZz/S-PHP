<?php

namespace App\Services;

class Auth
{
    public static function login($user)
    {
        if (is_array($user)) {
            unset($user['password']);
            $_SESSION['user'] = $user['username'];
            setcookie('User', $user['username'], time() + (86400 * 30), path: "/");
        } else {
            throw new \Exception("Expected user data as array, received something else.");
        }
    }

    public static function logout()
    {
        if (!empty($_COOKIE["User"])) {
            session_destroy();
            setcookie('User', '', time() - 3600, '/');
        } else {
            throw new \Exception("Cannot logout until user is logged in ");
        }
    }

    public static function user()
    {
        return $_SESSION['user'];
    }
}