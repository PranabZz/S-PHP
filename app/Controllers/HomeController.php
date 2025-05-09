<?php

namespace App\Controllers;

use App\Models\Users;
use Exception;
use Sphp\Core\ApiController;
use Sphp\Core\Controller;
use Sphp\Core\Request;
use Sphp\Core\View;
use Sphp\Services\Auth;

/**
 * HomeController Controller
 */
class HomeController extends ApiController
{
    // TODO: Implement Controller functionality
    protected $user;
    public function __construct(){

        $this->user = new Users();
    }

    public function index(){
        View::render('welcome.php');
    }

    public function register(){
        try{
            $request = Request::request();
            $this->user->save($request);
        }catch(Exception){
            
        }
    }

    public function login(){
        try{
            $request  = Request::request();

            $bearer_token = Auth::login($request);

            return $this->successResponse("Login sucess" , $bearer_token);

        }catch(Exception){
            return $this->errorResponse("Error try again");
        } 
    }

    public function welcome(){
        try{
            return $this->successResponse("Sucessfully loged in user");
        }catch(Exception){
            return $this->errorResponse("Failed to connect", 404);
        }
    }
}
