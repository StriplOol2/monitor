<?php

namespace MonitorBundle\Command;

use MonitorBundle\Service\SearchService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class SearchDaemonCommand extends Command
{
    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * SearchDaemonCommand constructor.
     * @param SearchService $searchService
     * @param LoggerInterface $logger
     */
    public function __construct(SearchService $searchService, LoggerInterface $logger)
    {
        $this->searchService = $searchService;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('monitor:search:run')
            ->setDescription('search daemon');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $pid = pcntl_fork();
//
//        if ($pid === -1) {
//            throw new \RuntimeException('Could not fork the process');
//        } elseif ($pid > 0) {
//            // we are the parent process
//            $output->writeln('Daemon created with process ID ' . $pid);
//        } else {
//            file_put_contents(getcwd() . '/search_daemon.pid', posix_getpid());


            $this->logger->info('run searches');
            $this->searchService->runActualActiveSearches();
            $this->logger->info('end run searches');
//
//        }
    }
}
