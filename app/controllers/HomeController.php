<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\User;
use App\Models;

class HomeController
{
    public function index()
    {

        $user = new User();
        $req = new Request;
        $request = $req->request();
        
        $data = $user->select(['id','username']);

        View::render('test.php', $data);
    }

    public function create()
    {

        $user = new User();
        $req = new Request;
        $request = $req->request();
        
        $user->create($request);

        redirect('/');
    }

    public function edit($id)
    {
        // $id = $_GET['id'];
        $user = new User();
        $data = $user->findByID($id);

        View::render('edit.php', $data);
    }

    public function update()
    {
        $user = new User();
        $req = new Request;
        $request = $req->request();
        $id = $request['id'];
        $user->update($request , $id);

        redirect('/');
    }

    public function delete()
    {
        
        $user = new User();
        $req = new Request;
        $request = $req->request();
        $id = $request['id'];
        $user->delete($id, asd);

        redirect('/');
    }

    public function test()
    {

        $data = [
            'test' => ["nested_message" => "nested message"]
        ];

        View::render('index.php', $data);
    }
}

