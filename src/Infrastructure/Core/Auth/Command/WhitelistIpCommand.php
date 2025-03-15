<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Command;

use Predis\Client as Redis;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'auth:ip:whitelist',
    description: 'Add or remove IP addresses from the whitelist'
)]
class WhitelistIpCommand extends Command
{
    private const string WHITELIST_PREFIX = 'ip_whitelist:';

    public function __construct(
        private readonly Redis $redis
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('ip', InputArgument::REQUIRED, 'IP address to manage')
            ->addOption('remove', 'r', InputOption::VALUE_NONE, 'Remove IP from whitelist');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ip = $input->getArgument('ip');
        $remove = $input->getOption('remove');

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $output->writeln(sprintf('<error>Invalid IP address: %s</error>', $ip));
            return Command::FAILURE;
        }

        $key = self::WHITELIST_PREFIX . $ip;

        if ($remove) {
            $this->redis->del($key);
            $output->writeln(sprintf('<info>IP %s removed from whitelist</info>', $ip));
        } else {
            $this->redis->set($key, '1');
            $output->writeln(sprintf('<info>IP %s added to whitelist</info>', $ip));
        }

        return Command::SUCCESS;
    }
} 