<?php

namespace App\Controllers;

require_once './app/core/View.php';
require_once './app/core/Request.php';
require_once './app/controllers/Controller.php';
require_once './app/services/Auth.php';

use App\Core\View;
use App\Core\Request;
use App\Services\Auth;

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
}

