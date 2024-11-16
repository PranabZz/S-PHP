<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\View;

class HomeController
{
    public function index()
    {

        $data = [
            'message' => ["nested_message" => "nested message"]
        ];

        View::render('test.php', $data);
    }
}

