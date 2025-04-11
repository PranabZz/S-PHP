<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Core\View;
use App\Core\Request;
use App\Models\User;
use App\Services\Auth;
use App\Models;


class RegisterController extends Controller
{
    public function index()
    {
        View::render('Auth/register.php');
    }

    public function login()
    {
        $req = new Request;
        $request = $req->request();

        $username = $request["username"];
        $password = $request["password"];

        $user = new User;

        $query = $user->getUsername($username);
        
        $query = 'SELECT * FROM `users` WHERE `username` = :username';
        $params = array(':username' => $username);

        $result = $this->db->query($query, $params);

        if (!empty($result) && is_array($result[0])) {
            $user = $result[0];

            if (password_verify($password, $user['password'])) {
                Auth::login($user);
                redirect('/home');
                return;
            }
        }

        redirect('/login', 'Error in login, try again');
    }


    public function logout()
    {
        Auth::logout();
        redirect('/login');
    }
}

