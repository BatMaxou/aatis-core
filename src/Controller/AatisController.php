<?php

namespace App\Controller;

use Aatis\Routing\Entity\Route;
use Aatis\Routing\Controller\AbstractHomeController;
use Aatis\DependencyInjection\Interface\ContainerInterface;

class AatisController extends AbstractHomeController
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    #[Route('/')]
    public function home(): void
    {
        parent::home();
    }

    #[Route('/hello')]
    public function hello(): void
    {
        require_once $_ENV['DOCUMENT_ROOT'].'/../views/pages/hello.php';
    }

    #[Route('/hello/{name}')]
    public function helloName(string $name): void
    {
        require_once $_ENV['DOCUMENT_ROOT'].'/../views/pages/helloName.php';
    }
}
