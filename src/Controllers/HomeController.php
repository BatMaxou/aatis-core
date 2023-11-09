<?php

namespace Aatis\Core\Controllers;

use Aatis\Core\Entity\Container;

class HomeController extends AbstractController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function home(): void
    {
        require_once $_ENV['DOCUMENT_ROOT'].'/../views/home.php';
    }
}
