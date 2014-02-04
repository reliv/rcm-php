<?php

namespace Rcm\Model;

use \Zend\Log\Logger as ZendLogger;

class Logger
{
    /**
     * @var ZendLogger
     */
    private $logger;
    private $sendToBrowser = false;
    private $sendToCli = false;
    private $outputStarted = false;

    public function __construct(ZendLogger $logger)
    {
        $this->logger = $logger;
    }


    public function logMessage($message)
    {
        $this->logger->info(strip_tags($message));

        if ($this->sendToBrowser) {
            $this->sendMessageToBrowser($message);
        }

        if ($this->sendToCli) {
            $this->sendMessageToCli($message);
        }

    }

    public function sendMessageToBrowser($message)
    {
        if (!$this->outputStarted) {
            ob_start();
            echo '<!DOCTYPE html><html lang="en"><head></head><body>';
            $this->outputStarted = true;
        }

        echo strip_tags($message, '<h1><p><br><hr>');
        echo '<br />';
        echo str_repeat(" ", 6024), "\n";
        ob_flush();
        flush();
    }

    public function sendMessageToCli($message)
    {
        echo $message;
        echo "\n";
    }

    /**
     * @param boolean $sendToCli
     */
    public function setSendToCli($sendToCli)
    {
        $this->sendToCli = $sendToCli;
    }

    /**
     * @param boolean $sendToBrowser
     */
    public function setSendToBrowser($sendToBrowser)
    {
        $this->sendToBrowser = $sendToBrowser;
    }

}