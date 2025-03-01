<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\User;
use App\Models;
use App\Services\SendMail;


class HomeController
{
    public function index()
    {

        $user = new User();
        $req = new Request;
        $request = $req->request();

        $data = $user->select(['id', 'username']);

        View::render('test.php', $data);
    }

    public function create()
    {

        $user = new User();
        $req = new Request;
        $request = $req->request();

        $user = $user->create($request);
    
        SendMail::sendMail('pranabkc191@gmail.com', 'Welcome', 'Welcome to our website!');
        redirect('/');
    }

    public function edit($id)
    {
        $user = new User();
        $data = $user->findByID($id);

        View::render('edit.php', $data);
    }

    public function update($id)
    {
        $user = new User();
        $req = new Request;
        $request = $req->request();

        $user->update($request, $id);

        redirect('/');
    }

    public function delete($id)
    {

        $user = new User();
        $req = new Request;
        $request = $req->request();

        $user->delete($id);

        redirect('/');
    }

    public function portfolio()
    {
        View::render('static.html');
    }

    public function test()
    {

        $data = [
            'test' => ["nested_message" => "nested message"]
        ];

        View::render('index.php', $data);
    }
}

