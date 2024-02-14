<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Aatis\Logger\Enum\LogLevel;
use Aatis\Routing\Entity\Route;
use Aatis\EventDispatcher\Service\EventDispatcher;
use Aatis\Tester\Common\Interface\WriterInterface;
use Aatis\Tester\EventDispatcher\Event\CustomEvent;
use Aatis\Routing\Controller\AbstractHomeController;
use Aatis\DependencyInjection\Interface\ContainerInterface;
use Aatis\Tester\EventDispatcher\Event\CustomStoppableEvent;
use Aatis\DependencyInjection\Exception\FileNotFoundException;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;

class AatisController extends AbstractHomeController
{
    public function __construct(
        ContainerInterface $container,
        TemplateRendererInterface $templateRenderer,
        private readonly LoggerInterface $logger,
        private readonly WriterInterface $writer,
        private readonly EventDispatcher $eventDispatcher,
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

    #[Route('/container')]
    public function container(): void
    {
        dd($this->container);
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
            'title' => 'Hello '.$name.' !',
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

    #[Route('/logger')]
    public function logger(): void
    {
        $this->logger->info('Logger is working !');
        $this->render('/pages/helloName.tpl.php', [
            'title' => 'Hello logger !',
            'name' => 'Logger',
        ]);
    }

    #[Route('/logger/{specific}')]
    public function loggerSpecific(string $specific): void
    {
        $this->logger->log(LogLevel::DEBUG, 'Logger is {test.context} !', ['test.context' => $specific]);
        $this->logger->info('Logger is {test.context} !', ['test.context' => $specific]);
        $this->logger->notice('Logger is {test.context} !', ['test.context' => $specific]);
        $this->logger->warning('Logger is {test.context} !', ['test.context' => $specific]);
        $this->logger->error('Logger is {test.context} !', ['test.context' => $specific]);
        $this->logger->critical('Logger is {test.context} !', ['test.context' => $specific]);
        $this->logger->alert('Logger is {test.context} !', ['test.context' => $specific]);
        $this->logger->emergency('Logger is {test.context} !', ['test.context' => $specific]);

        $this->render('/pages/helloName.tpl.php', [
            'title' => 'Hello logger '.$specific.' !',
            'name' => 'Logger',
        ]);
    }

    #[Route('/exception')]
    public function loggerError(): void
    {
        throw new FileNotFoundException('Test exception', 30);
    }

    #[Route('/error')]
    public function loggerException(): void
    {
        trigger_error('Test error', E_USER_ERROR);
    }

    #[Route('/permission')]
    public function permission(): void
    {
        exec('ls -al ../', $outputLs);
        exec('whoami', $outputUser);

        dd($outputLs, $outputUser);
    }

    #[Route('/event')]
    public function event(): void
    {
        $event = new CustomEvent('This is a message from Custom Event !');
        $this->eventDispatcher->dispatch($event);
    }

    #[Route('/stoppable-event')]
    public function stoppableEvent(): void
    {
        $event = new CustomStoppableEvent('This is a message from Stoppable Event !');
        $this->eventDispatcher->dispatch($event);
    }
}
