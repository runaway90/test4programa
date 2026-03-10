<?php

namespace App\Command;

use App\Application\Service\UserSyncService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-users',
    description: 'Sync users from JSONPlaceholder API',
)]
class SyncUsersCommand extends Command
{
    public function __construct(
        private UserSyncService $userSyncService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Sync usesr start');

        try {
            $this->userSyncService->sync();
            $io->success(sprintf('Done successfully sync users from JSONPlaceholder API'));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error message: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
