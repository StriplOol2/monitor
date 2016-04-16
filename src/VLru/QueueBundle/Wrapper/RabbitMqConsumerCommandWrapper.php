<?php

namespace VLru\QueueBundle\Wrapper;

use OldSound\RabbitMqBundle\Command\ConsumerCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsumerCommandWrapper
 *
 * @package VLru\QueueBundle\Wrapper
 */
class RabbitMqConsumerCommandWrapper extends ConsumerCommand
{
    use DaemonCommandTrait {
        execute as doExecute;
    }

    /** @var  string */
    protected $pidFileName;

    /** @var LoggerInterface */
    protected $log;

    public function __construct(LoggerInterface $log)
    {
        parent::__construct();
        $this->log = $log;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('pid-file-name', InputArgument::REQUIRED, 'Pid file name');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->pidFileName = $input->getArgument('pid-file-name');
        $this->doExecute($input, $output);
    }

    /**
     * @param string $message
     * @param \Exception|null $cause
     */
    protected function handleError($message, \Exception $cause = null)
    {
        $this->log->error($message, ['exception' => $cause]);
        throw new \RuntimeException($message, 0, $cause);
    }

    /**
     * @param string $message
     */
    protected function logDebug($message)
    {
        $this->log->debug($message);
    }

    /**
     * @return string
     */
    protected function getPidFileName()
    {
        return $this->pidFileName;
    }
}