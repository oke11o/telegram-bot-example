<?php

namespace App\Tests\Unit\Telegram\Request;

use App\Telegram\Controller\CancelController;
use App\Telegram\Controller\ChangeLocaleController;
use App\Telegram\Controller\CreateParticipantController;
use App\Telegram\Controller\HomeController;
use App\Telegram\Controller\MyParticipantController;
use App\Telegram\Request\RequestFactory;
use App\Telegram\State\State;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

class RequestFactoryTest extends \PHPUnit\Framework\TestCase
{
    private const CHAT_ID = 1231231;
    /**
     * @var ContainerInterface|ObjectProphecy
     */
    private $container;
    /**
     * @var RequestFactory
     */
    private $factory;

    public function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->factory = new RequestFactory($this->container->reveal());

        foreach (RequestFactory::getSubscribedServices() as $serviceName) {
            $this->container->get($serviceName)->willReturn($this->prophesize($serviceName)->reveal());
        }
    }

    /**
     * @test
     * @dataProvider createExamples
     */
    public function create($chatId, $text, $callback, $state, $controller, $action)
    {
        $request = $this->factory->create($chatId, $text, '', $state);

        $this->assertInstanceOf($controller, $request->getController());
        $this->assertEquals($action, $request->getActionName(), 'Action name');
        $this->assertEquals($chatId, $request->getArguments()->getChatId(), 'Chat Id');
        $this->assertEquals($text, $request->getArguments()->getText(), 'Text');
    }

    public function createExamples()
    {
        return [
            [
                'chatId' => self::CHAT_ID,
                'text' => '/start',
                'callback' => '',
                'state' => new State('hello', 'index'),
                'controller' => HomeController::class,
                'action' => 'index',
            ],
            'change.locale' => [
                'chatId' => self::CHAT_ID,
                'text' => ChangeLocaleController::COMMAND_NAME,
                'callback' => '',
                'state' => new State('hello', 'index'),
                'controller' => ChangeLocaleController::class,
                'action' => 'index',
            ],
            'change.locale.from.state.choose.locale' => [
                'chatId' => self::CHAT_ID,
                'text' => 'text',
                'callback' => '',
                'state' => new State(ChangeLocaleController::COMMAND_NAME, 'index'),
                'controller' => ChangeLocaleController::class,
                'action' => 'chooseLocale',
            ],
            'change.locale.cancel' => [
                'chatId' => self::CHAT_ID,
                'text' => CancelController::COMMAND_NAME,
                'callback' => '',
                'state' => new State(ChangeLocaleController::COMMAND_NAME, 'index'),
                'controller' => CancelController::class,
                'action' => 'cancel',
            ],
            'create.participant.index' => [
                'chatId' => self::CHAT_ID,
                'text' => CreateParticipantController::COMMAND_NAME,
                'callback' => '',
                'state' => new State('hello', 'index'),
                'controller' => CreateParticipantController::class,
                'action' => 'index',
            ],
            'create.participant.from.state' => [
                'chatId' => self::CHAT_ID,
                'text' => 'asdfasf',
                'callback' => '',
                'state' => new State(CreateParticipantController::COMMAND_NAME, 'sdfdsfsdf'),
                'controller' => CreateParticipantController::class,
                'action' => 'sdfdsfsdf',
            ],
            'create.participant.from.state.cancel' => [
                'chatId' => self::CHAT_ID,
                'text' => CancelController::COMMAND_NAME,
                'callback' => '',
                'state' => new State(CreateParticipantController::COMMAND_NAME, 'sdfdsfsdf'),
                'controller' => CancelController::class,
                'action' => 'cancel',
            ],
            'my.participants.index' => [
                'chatId' => self::CHAT_ID,
                'text' => MyParticipantController::COMMAND_NAME,
                'callback' => '',
                'state' => new State('hello', 'index'),
                'controller' => MyParticipantController::class,
                'action' => 'index',
            ],
            'my.participants.from.state' => [
                'chatId' => self::CHAT_ID,
                'text' => 'asdfadsfaf',
                'callback' => '',
                'state' => new State(MyParticipantController::COMMAND_NAME, 'index'),
                'controller' => MyParticipantController::class,
                'action' => 'index',
            ],
            'my.participants.from.state.cancel' => [
                'chatId' => self::CHAT_ID,
                'text' => CancelController::COMMAND_NAME,
                'callback' => '',
                'state' => new State(MyParticipantController::COMMAND_NAME, 'sdfdsfsdf'),
                'controller' => CancelController::class,
                'action' => 'cancel',
            ],
        ];
    }
}
