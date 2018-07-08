<?php

namespace App\Telegram\UpdateHandler;


use App\Entity\User;
use App\Telegram\State\ChangeLocaleState;
use App\Telegram\State\TelegramStateInterface;
use App\Telegram\Type\HandleResponse;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\Type\ReplyMessageFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;

class ChangeLocaleHandler extends AbstractHandler implements TelegramUpdateHandlerInterface
{

    /**
     * @var ReplyMessageFactory
     */
    private $factory;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        ReplyMessageFactory $factory,
        TranslatorInterface $translator,
        EntityManagerInterface $em
    ) {
        $this->factory = $factory;
        $this->em = $em;

        parent::__construct($translator);
    }

    public function handle(Update $update, User $user, TelegramStateInterface $state = null): HandleResponse
    {
        if ($state) {
            return $this->handleState($update, $user, $state);
        }

        $state = $this->createState();
        $buttons = new ReplyKeyboardMarkup(
            $this->getButtons($user)
        );

        $chatId = $update->getMessage()->getChat()->getId();
        $text = 'change_locale';

        return new HandleResponse($this->factory->create($chatId, $text, $buttons, $user->getRealLocale()), $state);
    }

    private function handleState(Update $update, User $user, TelegramStateInterface $state): HandleResponse
    {
        $chatId = $update->getMessage()->getChat()->getId();
        $messageText = strtolower($update->getMessage()->getText());
        $resetState = false;

        //validation
        if (!\in_array($messageText, ['en', 'ru'])) {
            $text = 'validate.change_locale';

            $buttons = new ReplyKeyboardMarkup(
                $this->getButtons($user)
            );
        } else {
            $user->setLocale($messageText);
            $this->em->flush();

            $resetState = true;
            $text = 'form.change_locale.success';
            $buttons = new ReplyKeyboardMarkup(
                $this->getStdButtons($user)
            );
        }

        return new HandleResponse(
            $this->factory->create($chatId, $text, $buttons, $user->getRealLocale()),
            $state,
            $resetState
        );
    }


    /**
     * @param User $user
     * @return array
     */
    protected function getButtons(User $user): array
    {
        return [
            [
                [
                    'text' => 'EN',
                ],
                [
                    'text' => 'RU',
                ],
            ],
            [
                [
                    'text' => $this->translator->trans('cancel', [], null, $user->getRealLocale()),
                ],
            ],
        ];
    }

    private function createState(): ChangeLocaleState
    {
        $state = new ChangeLocaleState();

        return $state;
    }
}