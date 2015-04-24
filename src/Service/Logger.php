<?php

namespace Rcm\Service;

use \Zend\Log\Logger as ZendLogger;

/**
 * Rcm Logger.
 *
 * Rcm Logger class to be used through out the system when logging is required
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class Logger
{
    /**
     * @var ZendLogger
     */
    protected $logger;

    protected $sendToBrowser = false;
    protected $sendToCli = false;
    protected $outputStarted = false;

    /**
     * Constructor
     *
     * @param ZendLogger $logger Zend Logger
     */
    public function __construct(ZendLogger $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Log a message
     *
     * @param string $message Message to be logged
     */
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

    /**
     * Send message to browser
     *
     * @param string $message Message to be sent
     * @codeCoverageIgnore
     */
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

    /**
     * Send message to Cli
     *
     * @param string $message Message to be sent
     * @codeCoverageIgnore
     */
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
     * Set send to browser
     *
     * @param boolean $sendToBrowser
     * @codeCoverageIgnore
     */
    public function setSendToBrowser($sendToBrowser)
    {
        $this->sendToBrowser = $sendToBrowser;
    }
}
