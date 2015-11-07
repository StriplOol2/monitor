<?php

namespace MonitorBundle\Command;

use MonitorBundle\Service\AdvertService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetActualAdvertCommand extends Command
{
    /** @var AdvertService */
    protected $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
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
        $searchId = $input->getOption('search_id');
        $output->writeln('getting started');
        $this->service->generateLastAdverts($searchId);
        $output->writeln('getting end');
    }
}
