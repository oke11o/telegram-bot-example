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
        $io = new SymfonyStyle($input, $output);

        $updates = $this->api->getUpdates();
        foreach ($updates as $update) {
            $this->handler->handleUpdate($update);
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
