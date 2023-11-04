<?php

namespace Aatis\Core\Controllers;

use App\Controllers\AbstractController;

class HomeController extends AbstractController
{
    public function home()
    {
        require_once(ROOT . '../views/home.php');
    }
}
