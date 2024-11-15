<?php


session_start();


function redirect($url, $message = "")
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    }
    header("Location:" . $url);
}


function dd($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die();
}