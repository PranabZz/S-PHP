<?php 

namespace Sphp\Core;

class Request{
    public static function request()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $_POST;
        }
    }
}