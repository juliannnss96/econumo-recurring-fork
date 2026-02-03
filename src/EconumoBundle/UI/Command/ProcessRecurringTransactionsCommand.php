<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Command;

use App\EconumoBundle\Application\RecurringTransaction\RecurringTransactionService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'econumo:process-recurring',
    description: 'Process due recurring transactions',
)]
class ProcessRecurringTransactionsCommand extends Command
{
    public function __construct(
        private readonly RecurringTransactionService $recurringTransactionService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Processing Recurring Transactions');

        try {
            $count = $this->recurringTransactionService->processDueTransactions();
            $io->success(sprintf('Successfully processed %d transactions.', $count));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
