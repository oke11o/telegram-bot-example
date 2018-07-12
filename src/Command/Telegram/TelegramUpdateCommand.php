<?php

namespace App\Command\Telegram;

use App\Telegram\TelegramBotApi;
use App\Telegram\TelegramHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TelegramUpdateCommand extends Command
{
    protected static $defaultName = 'app:telegram:update';

    /**
     * @var TelegramBotApi
     */
    private $api;
    /**
     * @var TelegramHandler
     */
    private $handler;
    /**
     * @var boolean
     */
    private $shouldStop = false;
    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(TelegramBotApi $api, TelegramHandler $handler)
    {
        $this->api = $api;
        $this->handler = $handler;

        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        declare(ticks = 1);
        pcntl_signal(SIGINT, [$this, 'doInterrupt']);
        pcntl_signal(SIGTERM, [$this, 'doTerminate']);

        $count = 0;
        while (true) {
            $updates = $this->api->getUpdates();
            $lastUpdateId = null;
            foreach ($updates as $update) {
                $count++;
                $lastUpdateId = $update->getUpdateId();
                $this->io->text(sprintf('Iteration=%d UpdateId=%d MessageId=%d', $count, $lastUpdateId, $update->getMessage()->getMessageId()));

                $replyMessage = $this->handler->handleUpdate($update);
                $this->api->sendMessage($replyMessage);
                if ($this->shouldStop) {
                    break;
                }
            }
            if ($lastUpdateId) {
                $this->api->getUpdates($lastUpdateId + 1, 1);//reset updates!!!
            }

            $this->io->text(sprintf('Sleeping'));
            sleep(1);
            if ($this->shouldStop) {
                break;
            }
        }


        $this->io->success('DONE.');
    }




    /**
     * Ctrl-C
     */
    private function doInterrupt()
    {
        $this->shouldStop = true;
        $this->io->error('Interruption signal received.');
    }

    /**
     * kill PID
     */
    private function doTerminate()
    {
        $this->shouldStop = true;
        $this->io->error('Termination signal received.');
    }
}
