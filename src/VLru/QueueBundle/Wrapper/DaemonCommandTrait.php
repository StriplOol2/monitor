<?php

namespace VLru\QueueBundle\Wrapper;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsumerCommandWrapper
 *
 * @package VLru\QueueBundle\Wrapper
 */
trait DaemonCommandTrait
{
    /** @var  string */
    protected $daemonPidFileName;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug("Force start daemon begin");
        $this->daemonPidFileName = $this->getPidFileName();

        if (!$this->running()) {
            $this->logDebug("Daemon wasn't running");

            $this->start($input, $output);
        } else {
            if ($this->crashed()) {
                $this->logDebug("Daemon was crashed");
                $this->removePidFile();

                $this->logDebug("Pid file force cleaned, try to start daemon");
                $this->start($input, $output);
            } else {
                $this->logDebug("Daemon is still running, no need to start it");
            }
        }
    }

    /**
     * @return bool
     */
    protected function crashed()
    {
        $this->logDebug("crash test start");

        if (!$pidResource = \fopen($this->daemonPidFileName, 'r')) {
            $this->handleError("Cannot open pid file");
        }

        $this->logDebug("Pid file opened");

        if (!$pid = \fread($pidResource, \filesize($this->daemonPidFileName))) {
            $this->resourceClose($pidResource);
            $this->handleError("Cannot read pid file");
        }

        $pid = \intval(\trim($pid));

        $this->logDebug("Pid read:" . $pid);

        $this->resourceClose($pidResource);

        /** try to send null signal */
        \exec(\sprintf("kill -s 0 %d", $pid), $output, $result);

        $this->logDebug("Crash test ended with result: " . $result);
        return $result !== 0;
    }

    /**
     * @return bool
     */
    protected function running()
    {
        \clearstatcache(true, $this->daemonPidFileName);
        return (\file_exists($this->daemonPidFileName));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function start(InputInterface $input, OutputInterface $output)
    {
        $this->logDebug("Start daemon begin");

        if ($this->running()) {
            $this->handleError("Daemon already running");
        }

        if (!$pidResource = \fopen($this->daemonPidFileName, 'x')) {
            $this->handleError("Pid file cannot be opened");
        }

        $this->logDebug("Pid file created");

        $EWOULDBLOCK = false;
        if (!\flock($pidResource, \LOCK_EX | \LOCK_NB, $EWOULDBLOCK)) {
            $errReason = ($EWOULDBLOCK) ?
                "Pid file already blocked" : "Cannot lock pid: {$this->daemonPidFileName}";

            $this->resourceFree($pidResource);
            $this->handleError($errReason);
        }

        $this->logDebug("Pid file locked");

        if (!$pid = \sprintf("%d\n", $this->getPid())) {
            $this->resourceFree($pidResource);
            $this->handleError("Cannot format daemon pid");
        }

        $pidLen = \strlen($pid);
        $pid = \intval(\trim($pid));

        $this->logDebug("Pid generated:" . $pid);

        if (!\ftruncate($pidResource, $pidLen)) {
            $this->resourceFree($pidResource);
            $this->handleError("Cannot truncate pid file");
        }

        if (!fwrite($pidResource, $pid, $pidLen)) {
            $this->resourceFree($pidResource);
            $this->handleError("Pid file cannot be written");
        }

        $this->logDebug("Pid written to file");

        try {
            $this->logDebug("Daemon command launching");

            parent::execute($input, $output);

            $this->logDebug("Daemon command completed");
        } catch (\Exception $commandExecutionException) {
            $this->handleError("Unexpected daemon error", $commandExecutionException);
        }

        $this->resourceFree($pidResource);
        $this->logDebug("Pid file removed, daemon stopped");
    }

    protected function removePidFile()
    {
        \clearstatcache(true, $this->daemonPidFileName);
        if (\file_exists($this->daemonPidFileName)) {
            \unlink($this->daemonPidFileName);
        }
        \clearstatcache(true, $this->daemonPidFileName);
    }

    /**
     * @param $fileResource
     */
    protected function resourceClose($fileResource)
    {
        \fclose($fileResource);
    }

    /**
     * @param $fileResource
     */
    protected function resourceFree($fileResource)
    {
        \fclose($fileResource);
        \unlink($this->daemonPidFileName);
    }



    /**
     * @return int
     */
    protected function getPid()
    {
        return \posix_getpid();
    }

    /**
     * @return string
     */
    abstract protected function getPidFileName();

    /**
     * @param string $message
     * @param \Exception|null $cause
     */
    abstract protected function handleError($message, \Exception $cause = null);

    /**
     * @param string $message
     */
    abstract protected function logDebug($message);
}