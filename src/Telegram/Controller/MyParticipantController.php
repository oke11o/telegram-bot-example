<?php

namespace App\Telegram\Controller;

use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ParticipantRepository;
use App\Telegram\Request\Arguments;
use App\Telegram\Response\ClearReplyMessage;
use App\Telegram\Response\Response;
use App\Telegram\State\Data\ParticipantListDto;
use App\Telegram\State\State;
use App\Telegram\StateFactory;

class MyParticipantController implements TelegramControllerInterface
{
    public const COMMAND_NAME = 'my.participant';
    public const LIMIT = 5;
    /**
     * @var StateFactory
     */
    private $stateFactory;
    /**
     * @var ParticipantRepository
     */
    private $participantRepository;

    public function __construct(StateFactory $stateFactory, ParticipantRepository $participantRepository)
    {
        $this->stateFactory = $stateFactory;
        $this->participantRepository = $participantRepository;
    }

    public function index(Arguments $arguments, State $state, User $user): Response
    {
        $list = $this->getParticipants($user);

        if ($state->getData()) {
            $data = ParticipantListDto::fromArray($state->getData());
        } else {
            $data = new ParticipantListDto(count($list), 0, self::LIMIT);
        }

        if ($callback = $arguments->getCallbackData()) {

            if (is_numeric($callback)) {
                return $this->show($arguments, $state, $user);
            }

            if ($callback === 'prev' || $callback === 'next') {
                $cur = $data->getCurrentPage();
                if ($callback === 'prev') {
                    $cur--;
                } elseif ($callback === 'next') {
                    $cur++;
                }
                if ($cur < 0) {
                    $cur = $data->getTotalPage();
                }
                if ($cur > $data->getTotalPage()) {
                    $cur = 0;
                }
            }
            $data = new ParticipantListDto(count($list), $cur, self::LIMIT);

        }
        $list = $this->paginateList($list, $data);

        $message = new ClearReplyMessage(
            $arguments->getChatId(),
            'your.'.self::COMMAND_NAME,
            $this->getButtons($list, $data),
            ClearReplyMessage::BUTTON_TYPE_INLINE
        );

        $newState = $this->stateFactory->create(self::COMMAND_NAME, 'index', $data->jsonSerialize());

        return new Response($message, $newState);
    }

    public function show(Arguments $arguments, State $state, User $user): Response
    {
        $callback = $arguments->getCallbackData();
        $participant = $this->getParticipant($callback, $user);


        $message = new ClearReplyMessage(
            $arguments->getChatId(),
            sprintf(
                'your.participant.id.%d
Amount: %d, Eth: %s',
                $participant->getId(),
                $participant->getAmount(),
                $participant->getEthWallet()
            ),
            $this->getStdButtons(),
            ClearReplyMessage::BUTTON_TYPE_SIMPLE
        );

        $newState = $this->stateFactory->create(self::COMMAND_NAME, 'index');

        return new Response($message, $newState);
    }

    /**
     * @param Participant[] $list
     * @return array
     */
    private function getButtons(array $list, ParticipantListDto $paginator)
    {
        $result = [];
        foreach ($list as $participant) {
            $result[] = [
                [
                    'text' => sprintf(
                        'ID: %d, Amount: %f, User: %s',
                        $participant->getId(),
                        $participant->getAmount(),
                        $participant->getUser()->getUsername()
                    ),
                    'callback_data' => $participant->getId(),
                ],
            ];
        }
        $result[] =
            [
                [
                    'text' => $paginator->getCurrentPage() === 0 ? 'To end' : 'prev',
                    'callback_data' => 'prev',
                ],
                [
                    'text' => $paginator->getCurrentPage() === $paginator->getTotalPage() ? 'To start' : 'next',
                    'callback_data' => 'next',
                ],
            ];

        return $result;
    }

    private function getStdButtons()
    {
        return [
            [
                ['text' => 'list'],
            ],
            [
                ['text' => 'main'],
            ],
        ];
    }

    private function getParticipant($id, User $user)
    {
        return $this->participantRepository->findOneByUser($id, $user);
    }

    private function getParticipants(User $user)
    {
        return $this->participantRepository->findByUser($user);
    }

    private function paginateList($list, ParticipantListDto $data)
    {
        $result = [];
        $start = $data->getCurrentPage() * $data->getLimit();
        for ($i = $start; $i < $start + $data->getLimit(); $i++) {
            if (isset($list[$i])) {
                $result[] = $list[$i];
            }
        }

        return $result;
    }
}