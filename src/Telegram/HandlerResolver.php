<?php

namespace App\Telegram;

use App\Entity\User;
use App\Telegram\State\TelegramStateInterface;
use App\Telegram\UpdateHandler\AboutHandler;
use App\Telegram\UpdateHandler\BuyHandler;
use App\Telegram\UpdateHandler\CancelHandler;
use App\Telegram\UpdateHandler\ChangeLocaleHandler;
use App\Telegram\UpdateHandler\OffersHandler;
use App\Telegram\UpdateHandler\SellHandler;
use App\Telegram\UpdateHandler\StartHandler;
use App\Telegram\UpdateHandler\TelegramUpdateHandlerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\Translation\Exception\InvalidArgumentException;
use Symfony\Component\Translation\TranslatorInterface;
use TelegramBot\Api\Types\Update;

class HandlerResolver implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * HandlerResolver constructor.
     * @param ContainerInterface $container
     * @param TranslatorInterface $translator
     */
    public function __construct(
        ContainerInterface $container,
        TranslatorInterface $translator
    ) {
        $this->container = $container;
        $this->translator = $translator;
    }

    /**
     * @param Update $update
     * @param User $user
     * @param null $state
     * @return TelegramUpdateHandlerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function resolve(Update $update, User $user, TelegramStateInterface $state = null): TelegramUpdateHandlerInterface
    {
        $text = $update->getMessage()->getText();
        $serviceName = $this->resolveServiceName($text, $state);

        if ($state) {
            $serviceName = $state->resolveHandlerName($serviceName);
        }


        return $this->container->get($serviceName);
    }

    /**
     * @return array The required service types, optionally keyed by service names
     */
    public static function getSubscribedServices()
    {
        $result = array_keys(self::getServiceList());

        return $result;
    }

    private function resolveServiceName($text, $state)
    {
        $handlers = $this->getServiceMap();

        //TODO: надо подумать
        if (\array_key_exists($text, $handlers)) {
            return $handlers[$text];
        }

        return StartHandler::class;
    }

    private static function getServiceList()
    {
        return [
            StartHandler::class => 'start_message',
            AboutHandler::class => 'about',
            BuyHandler::class => 'buy_eth',
            SellHandler::class => 'sell_eth',
            OffersHandler::class => 'my_orders',
            ChangeLocaleHandler::class => 'change_locale',
            CancelHandler::class => 'cancel',
        ];
    }

    /**
     * @return string[]
     * @throws \Exception
     */
    private function getServiceMap(): array
    {
        $result = [];
        $availableLocales = ['en', 'ru'];
        try {

            foreach (self::getServiceList() as $serviceName => $key) {
                $result[$serviceName::getCommandName()] = $serviceName;
                foreach ($availableLocales as $locale) {
                    $localeKey = $this->translator->trans($key, [], null, $locale);
                    $result[$localeKey] = $serviceName;
                }
            }
        } catch (InvalidArgumentException $exception) {
            $message = sprintf('Неверно указан ключ перевода в методе getServiceList()');
            throw new InvalidArgumentException($message, 0, $exception);
        } catch (\Exception $exception) {
            $message = sprintf('Method getCommandName() in class %s unexist.', $serviceName);
            throw new InvalidArgumentException($message, 0, $exception);
        }

        return $result;
    }
}