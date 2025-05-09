<?php

namespace Sphp\Core;

class Response
{
    public static function response($response_code,$message, $key = "message")
    {
        header('Content-Type: application/json');
        http_response_code($response_code);

        echo json_encode([$key => $message]);
    }
}