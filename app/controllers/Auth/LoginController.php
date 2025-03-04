<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Core\View;
use App\Core\Request;
use App\Models\User;
use App\Services\Auth;
use App\Models;


class LoginController extends Controller
{
    public function index()
    {
        View::render('login.php');
    }
    public function login()
    {
        $req = new Request;
        $request = $req->request();
       
        $username = $request["username"];
        $password = $request["password"];

        $query = 'SELECT * FROM `user` WHERE `username` = :username AND `password` = :password';
        $params = array(':username' => $username, ':password' => $password);

        $result = $this->db->query($query, $params);

        if (!empty($result) && is_array($result[0])) {
            Auth::login($result[0]);
            redirect('/home');
        } else {
            redirect('/login','error in login try again');
        }
    }

    public function logout(){
        Auth::logout();
        redirect('/login');
    }
}

