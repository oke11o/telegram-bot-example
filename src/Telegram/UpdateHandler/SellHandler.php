<?php


namespace App\Telegram\UpdateHandler;

use App\Entity\User;
use App\Telegram\State\TelegramStateInterface;
use App\Telegram\Type\HandleResponse;
use App\Telegram\Type\ReplyMessage;
use App\Telegram\Type\ReplyMessageFactory;
use Symfony\Component\Translation\TranslatorInterface;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;


class SellHandler extends AbstractHandler implements TelegramUpdateHandlerInterface
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

    public function handle(Update $update, User $user, TelegramStateInterface $state = null): HandleResponse
    {
        $buttons = new ReplyKeyboardMarkup(
            $this->getStdButtons($user)
        );

        $chatId = $update->getMessage()->getChat()->getId();
        $text = 'sell_eth';

        $coin = $this->coinRepository->findOneBySymbol('ETH');

//        $requestData = [
//            'coinWallet' => $stepData['coin_wallet'],
//            'fiatWallet' => $stepData['ym_wallet'],
//            'volume' => $stepData['eth_amount'],
//            'currency' => 'rub',
//            'coin' => ($coin ? $coin->getId() : null),
//            'paymentSystem' => ($paymentSystem ? $paymentSystem->getId() : null),
//            'type' => ParticipantTypeEnum::SELLER,
//        ];
//
//        $participant = $this->participantAction->create($requestData, $user);


        return new HandleResponse($this->factory->create($chatId, $text, $buttons, $user->getRealLocale()));
    }
}