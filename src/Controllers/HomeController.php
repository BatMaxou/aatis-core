<?php

namespace App\Controllers;

class HomeController
{
    public function home()
    {
        require_once(ROOT . '../views/home.php');
    }
}
