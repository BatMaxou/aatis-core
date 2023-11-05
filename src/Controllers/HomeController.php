<?php

namespace Aatis\Core\Controllers;

use Aatis\Core\Entity\Container;
use Aatis\Core\Controllers\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function home()
    {
        require_once(ROOT . '../views/home.php');
    }
}
