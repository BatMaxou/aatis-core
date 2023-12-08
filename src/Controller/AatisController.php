<?php

namespace App\Controller;

use Aatis\Routing\Entity\Route;
use Aatis\Routing\Controller\AbstractHomeController;
use Aatis\DependencyInjection\Interface\ContainerInterface;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;
use Aatis\Tester\Interface\WriterInterface;

class AatisController extends AbstractHomeController
{
    public function __construct(
        ContainerInterface $container,
        TemplateRendererInterface $templateRenderer,
        private readonly WriterInterface $writer
    ) {
        parent::__construct($container, $templateRenderer);
    }

    #[Route('/home')]
    public function home(): void
    {
        $this->render('/pages/home.tpl.php', [
            'title' => 'Home',
        ]);

        $this->writer->write();
    }

    #[Route('/hello')]
    public function hello(): void
    {
        $this->render('/pages/hello.tpl.php', [
            'title' => 'Hello Aatis ?',
        ]);
    }

    #[Route('/hello/{name}')]
    public function helloName(string $name): void
    {
        $this->render('/pages/helloName.tpl.php', [
            'title' => 'Hello ' . $name . ' !',
            'name' => $name,
        ]);
    }

    #[Route('/twig')]
    public function twig(): void
    {
        $this->render('/twig/twig.html.twig', [
            'title' => 'Twig',
        ]);
    }

    #[Route('/extra')]
    public function extra(): void
    {
        $this->render('/extra/home.extra.php', [
            'title' => 'Extra renderer',
        ]);
    }

    #[Route('/zebi')]
    public function zebi(): void
    {
        $this->render('/extra/home.zebi', [
            'title' => 'Zebi renderer',
        ]);
    }
}
