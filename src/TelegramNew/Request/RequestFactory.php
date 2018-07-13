<?php

namespace App\TelegramNew\Request;

use App\Entity\User;
use App\TelegramNew\Controller\CancelController;
use App\TelegramNew\Controller\ChangeLocaleController;
use App\TelegramNew\Controller\HomeController;
use App\TelegramNew\State;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use TelegramBot\Api\Types\Update;

class RequestFactory implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(string $text, State $state): Request
    {
        $arguments = new Arguments($text);
        if ($text === ChangeLocaleController::COMMAND_NAME) {
            $controller = $this->container->get(ChangeLocaleController::class);
            $actionName = 'index';
        } elseif ($state->getCommandName() === ChangeLocaleController::COMMAND_NAME) {
            if ($text === CancelController::COMMAND_NAME) {
                $controller = $this->container->get(CancelController::class);
                $actionName = 'cancel';
            } else {
                $controller = $this->container->get(ChangeLocaleController::class);
                $actionName = 'chooseLocale';
            }
        } else {
            $controller = $this->container->get(HomeController::class);
            $actionName = 'index';
        }


        return new Request($controller, $actionName, $arguments);
    }

    /**
     * @return array The required service types, optionally keyed by service names
     */
    public
    static function getSubscribedServices()
    {
        return [
            HomeController::class,
            ChangeLocaleController::class,
            CancelController::class,
        ];
    }
}