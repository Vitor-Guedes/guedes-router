<?php

namespace App\Controller;

class Index
{
    public function index($args)
    {
        echo 'controller';
    }

    public function login()
    {
        echo "login";
    }

    public function logout()
    {
        echo "logout";
    }
}