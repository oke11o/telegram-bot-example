<?php


namespace App\Telegram\UpdateHandler;

use App\Entity\User;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\Type\ReplyMessageFactory;
use Symfony\Component\Translation\TranslatorInterface;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;


class BuyHandler extends AbstractHandler implements TelegramUpdateHandlerInterface
{
    /**
     * @var ReplyMessageFactory
     */
    private $factory;

    public function __construct(ReplyMessageFactory $factory, TranslatorInterface $translator)
    {
        $this->factory = $factory;

        parent::__construct($translator);
    }

    public function handle(Update $update, User $user): ReplyMessage
    {
        $buttons = new ReplyKeyboardMarkup(
            $this->getStdButtons($user)
        );

        $chatId = $update->getMessage()->getChat()->getId();
        $text = 'buy_eth';

        return $this->factory->create($chatId, $text, $buttons, $user->getRealLocale());
    }
}