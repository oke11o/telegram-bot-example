<?php

namespace App\TelegramNew\Controller;

use App\Entity\User;
use App\Manager\ParticipantManager;
use App\TelegramNew\Request\Arguments;
use App\TelegramNew\Response\ClearReplyMessage;
use App\TelegramNew\Response\Response;
use App\TelegramNew\State\State;
use App\TelegramNew\StateFactory;
use Doctrine\ORM\EntityManagerInterface;

class CreateParticipantController implements TelegramControllerInterface
{
    public const COMMAND_NAME = 'create.participant';
    /**
     * @var StateFactory
     */
    private $stateFactory;
    /**
     * @var ParticipantManager
     */
    private $participantManager;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(StateFactory $stateFactory, EntityManagerInterface $em, ParticipantManager $participantManager)
    {
        $this->stateFactory = $stateFactory;
        $this->em = $em;
        $this->participantManager = $participantManager;
    }

    /**
     * @param Arguments $arguments
     * @param State $state
     * @param User $user
     * @return Response
     */
    public function index(Arguments $arguments, State $state, User $user): Response
    {
        $message = new ClearReplyMessage($arguments->getChatId(), 'text.your.ym.wallet', $this->getOneCancelButtons());

        $newState = $this->stateFactory->create(self::COMMAND_NAME, 'yaWallet');

        return new Response($message, $newState);
    }

    /**
     * @param Arguments $arguments
     * @param State $state
     * @param User $user
     * @return Response
     */
    public function yaWallet(Arguments $arguments, State $state, User $user): Response
    {
        $yandexWallet = $arguments->getText();
        if ($this->validateYandexWallet($yandexWallet)) {
            $message = new ClearReplyMessage(
                $arguments->getChatId(),
                'text.your.eth.wallet',
                $this->getOneCancelButtons()
            );

            $newState = $this->stateFactory->create(
                self::COMMAND_NAME,
                'ethWallet',
                ['yandex_wallet' => $yandexWallet]
            );

            return new Response($message, $newState);
        }

        $message = new ClearReplyMessage(
            $arguments->getChatId(),
            'validation.text.your.ym.wallet',
            $this->getOneCancelButtons()
        );

        return new Response($message, $state);
    }

    /**
     * @param Arguments $arguments
     * @param State $state
     * @param User $user
     * @return Response
     */
    public function ethWallet(Arguments $arguments, State $state, User $user): Response
    {
        $wallet = $arguments->getText();
        if ($this->validateETHWallet($wallet)) {
            $data = $state->getData();
            $data['eth_wallet'] = $wallet;

            $message = new ClearReplyMessage($arguments->getChatId(), 'text.amount', $this->getOneCancelButtons());

            $newState = $this->stateFactory->create(self::COMMAND_NAME, 'amount', $data);

            return new Response($message, $newState);
        }

        $message = new ClearReplyMessage(
            $arguments->getChatId(),
            'validate.your.eth.wallet',
            $this->getOneCancelButtons()
        );

        return new Response($message, $state);
    }

    /**
     * @param Arguments $arguments
     * @param State $state
     * @param User $user
     * @return Response
     */
    public function amount(Arguments $arguments, State $state, User $user): Response
    {
        $amount = $arguments->getText();
        if ($this->validateAmount($amount)) {
            $data = $state->getData();
            $data['amount'] = $amount;

            $message = new ClearReplyMessage(
                $arguments->getChatId(), 'please.agree', [
                    [
                        ['text' => 'yes'],
                        ['text' => 'no'],
                    ],
                ]
            );

            $newState = $this->stateFactory->create(self::COMMAND_NAME, 'agree', $data);

            return new Response($message, $newState);
        }

        $message = new ClearReplyMessage($arguments->getChatId(), 'validate.amount', $this->getOneCancelButtons());

        return new Response($message, $state);
    }

    public function agree(Arguments $arguments, State $state, User $user): Response
    {
        $agree = $arguments->getText();
        if ($agree === 'yes') {
            $participant = $this->participantManager->createParticipant($state->getData(), $user);
            $this->em->flush();

            $message = new ClearReplyMessage(
                $arguments->getChatId(),
                'success.create.participant.'.$participant->getId(),
                HomeController::getDefaultButtons()
            );

            $newState = $this->stateFactory->create(HomeController::COMMAND_NAME);

            return new Response($message, $newState);

        } elseif ($agree === 'no') {
            $message = new ClearReplyMessage(
                $arguments->getChatId(),
                self::COMMAND_NAME,
                HomeController::getDefaultButtons()
            );

            $newState = $this->stateFactory->create(HomeController::COMMAND_NAME);

            return new Response($message, $newState);
        }

        $message = new ClearReplyMessage(
            $arguments->getChatId(), 'please.agree', [
                [
                    ['text' => 'yes'],
                    ['text' => 'no'],
                ],
            ]
        );

        return new Response($message, $state);

    }


    private function getOneCancelButtons()
    {
        return [
            [
                ['text' => CancelController::COMMAND_NAME],
            ],
        ];
    }

    /**
     * @param string $wallet
     * @return bool
     */
    private function validateYandexWallet(string $wallet): bool
    {
        return strlen($wallet) === 32;
    }

    /**
     * @param string $wallet
     * @return bool
     */
    private function validateETHWallet(string $wallet): bool
    {
        return strlen($wallet) === 32 && strpos($wallet, '0x') === 0;
    }

    /**
     * @param string $wallet
     * @return bool
     */
    private function validateAmount(string $wallet): bool
    {
        return is_numeric($wallet);
    }
}