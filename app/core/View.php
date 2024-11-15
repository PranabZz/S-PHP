<?php

namespace App\Core;

class View
{
    public static function render($filename, $data = [])
    {
        extract($data);
        require('app/views/' . $filename);
    }
}