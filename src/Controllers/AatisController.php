<?php

namespace Aatis\Core\Controllers;

use Aatis\Core\Entity\Route;
use Aatis\DependencyInjection\Entity\Container;

class AatisController extends AbstractController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    #[Route('/', ['GET'])]
    public function home(): void
    {
        require_once $_ENV['DOCUMENT_ROOT'].'/../views/pages/home.php';
    }

    #[Route('/problem', ['GET'])]
    public function problem(): void
    {
        $errorTag = 'Redirect';
        $errorDescription = 'Something is wrong into your controller. Please check your routes.';
        require_once $_ENV['DOCUMENT_ROOT'].'/../views/errors/other.php';
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
