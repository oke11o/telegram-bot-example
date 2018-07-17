<?php

namespace App\Telegram\Type;

use App\Telegram\Response\ClearReplyMessage;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class ReplyMessageFactory
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function createFromClearMessage(ClearReplyMessage $clear)
    {
        if ($clear->getButtonType() === ClearReplyMessage::BUTTON_TYPE_INLINE) {
            $replyKeyboardMarkup = new InlineKeyboardMarkup($clear->getButtons());
        } else {
            $replyKeyboardMarkup = new ReplyKeyboardMarkup($clear->getButtons());
        }

        return $this->create($clear->getChatId(), $clear->getText(), $replyKeyboardMarkup);
    }

    public function create($chatId, $text, $replyMarkup = null, $locale = 'en')
    {
        $parseMode = 'markdown';
        try {
//            $text = $this->translator->trans($text, [], null, $locale);
        } catch (\Symfony\Component\Translation\Exception\InvalidArgumentException $e) {
            $this->logger->warning($e->getMessage());
        }

        return new ReplyMessage(
            $chatId,
            $text,
            $parseMode,
            $disablePreview = false,
            $replyToMessageId = null,
            $replyMarkup,
            $disableNotification = false
        );
    }
}