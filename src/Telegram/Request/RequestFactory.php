<?php

namespace App\Telegram\Request;

use App\Telegram\Controller\CancelController;
use App\Telegram\Controller\ChangeLocaleController;
use App\Telegram\Controller\CreateParticipantController;
use App\Telegram\Controller\HomeController;
use App\Telegram\Controller\MyParticipantController;
use App\Telegram\State\State;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;

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

    /**
     * TODO: Возможное решение. Создать цепочку резолверов. Для каждого контроллера.
     * Когда первый контроллер находит подходящий под себя запрос. Он прекращает цепочку.
     * Если все контроллеры пропустили, то вызывается дефолтный.
     */
    public function create(int $chatId, string $text, string $callbackData = null, State $state): Request
    {
        $arguments = new Arguments($chatId, $text, $callbackData);
        $controllerName = $state->getCommandName();

        if ($text === ChangeLocaleController::COMMAND_NAME) {
            $controller = $this->container->get(ChangeLocaleController::class);
            $actionName = 'index';

            return new Request($controller, $actionName, $arguments);
        }

        if ($controllerName === ChangeLocaleController::COMMAND_NAME) {
            if ($text === CancelController::COMMAND_NAME) {
                $controller = $this->container->get(CancelController::class);
                $actionName = 'cancel';
            } else {
                $controller = $this->container->get(ChangeLocaleController::class);
                $actionName = 'chooseLocale';
            }

            return new Request($controller, $actionName, $arguments);
        }

        if ($text === CreateParticipantController::COMMAND_NAME) {
            $controller = $this->container->get(CreateParticipantController::class);
            $actionName = 'index';

            return new Request($controller, $actionName, $arguments);
        }

        if ($controllerName === CreateParticipantController::COMMAND_NAME) {
            if ($text === CancelController::COMMAND_NAME || $text === 'main') {
                $controller = $this->container->get(CancelController::class);
                $actionName = 'cancel';
            } else {
                $controller = $this->container->get(CreateParticipantController::class);
                $actionName = $state->getAction();
            }

            return new Request($controller, $actionName, $arguments);
        }


        if ($text === MyParticipantController::COMMAND_NAME) {
            $controller = $this->container->get(MyParticipantController::class);
            $actionName = 'index';

            return new Request($controller, $actionName, $arguments);
        }

        if ($controllerName === MyParticipantController::COMMAND_NAME) {
            if ($text === CancelController::COMMAND_NAME || $text === 'main') {
                $controller = $this->container->get(CancelController::class);
                $actionName = 'cancel';
            } else {
                $controller = $this->container->get(MyParticipantController::class);
                $actionName = $state->getAction();
            }

            return new Request($controller, $actionName, $arguments);
        }

        $controller = $this->container->get(HomeController::class);
        $actionName = 'index';

        return new Request($controller, $actionName, $arguments);
    }

    /**
     * @return array The required service types, optionally keyed by service names
     */
    public static function getSubscribedServices()
    {
        return [
            HomeController::class,
            ChangeLocaleController::class,
            CancelController::class,
            CreateParticipantController::class,
            MyParticipantController::class,
        ];
    }
}