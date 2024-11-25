<?php

namespace App\Models;
use App\Core\Models;

/* TODO */

class User extends Models
{
    public function __construct()
    {
        $this->table = "user";
        $this->fillables = ['username', 'password'];
        parent::__construct();
    }

}