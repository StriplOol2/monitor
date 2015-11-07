<?php

namespace MonitorBundle\Command;

use MonitorBundle\Service\AdvertService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetActualAdvertCommand extends Command
{
    /** @var AdvertService */
    protected $service;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(AdvertService $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('monitor:adverts:actual')
            ->setDescription('get for search actual adverts')
            ->addOption('search_id', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pid = pcntl_fork();

        if ($pid === -1) {
            throw new \RuntimeException('Could not fork the process');
        } elseif ($pid > 0) {
            // we are the parent process
            $output->writeln('Daemon created with process ID ' . $pid);
        } else {
            $searchId = $input->getOption('search_id');
            $pidFilePath = getcwd() . "/search_command_{$searchId}.pid";
            \clearstatcache(true, $pidFilePath);
            if (file_exists($pidFilePath)) {
                if ($this->crashed($pidFilePath)) {
                    $this->removePidFile($pidFilePath);
                } else {
                    return;
                }
            }
            file_put_contents($pidFilePath, posix_getpid());
            $output->writeln('getting started');
            $this->service->generateLastAdverts($searchId);
            $this->removePidFile($pidFilePath);
            $output->writeln('getting end');
        }
    }

    protected function crashed($pidFilePath)
    {
        $this->logger->info("crash test start");

        if (!$pidResource = \fopen($pidFilePath, 'r')) {
            $this->logger->error("cannot open pid file");
        }

        $this->logger->info("pid file opened");

        if (!$pid = \fread($pidResource, \filesize($pidFilePath))) {
            $this->resourceClose($pidResource);
            $this->logger->error("cannot read pid file");
        }

        $pid = \intval(\trim($pid));

        $this->logger->info("pid read:" . $pid);

        $this->resourceClose($pidResource);

        /** try to send null signal */
        \exec(\sprintf("kill -s 0 %d", $pid), $output, $result);

        $this->logger->info("crash test ended with result: " . $result);
        return $result !== 0;
    }

    protected function removePidFile($pidFilePath)
    {
        \clearstatcache(true, $pidFilePath);
        if (\file_exists($pidFilePath)) {
            \unlink($pidFilePath);
        }
        \clearstatcache(true, $pidFilePath);
    }

    protected function resourceClose($fileResource)
    {
        \fclose($fileResource);
    }

    protected function resourceFree($fileResource, $pidFilePath)
    {
        \fclose($fileResource);
        \unlink($pidFilePath);
    }
}
