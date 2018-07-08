<?php

namespace App\Telegram\Type;

use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ReplyMessageFactory
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->translator = $translator;
        $this->logger = $logger;
    }

    public function create($chatId, $text, $replyMarkup = null)
    {
        $locales = ['en', 'ru'];
        $parseMode = 'markdown';
        try {
            $text = $this->translator->trans($text, [], null, $locales[\random_int(0, 1)]);
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