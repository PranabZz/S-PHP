<?php

namespace App\Models;
use Sphp\Core\Models;

/* TODO */

class Users extends Models
{
    public function __construct()
    {
        $this->table = "users";
        $this->fillables = ['email', 'name', 'password', 'verified'];
        parent::__construct();
    }

    public function save($data){
     
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->create($data);
    }

}