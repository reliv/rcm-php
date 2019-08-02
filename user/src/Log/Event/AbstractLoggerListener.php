<?php

namespace RcmUser\Log\Event;

use RcmUser\Event\AbstractListener;
use RcmUser\Log\Logger;
use Zend\EventManager\Event;

/**
 * Class AbstractLoggerListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractLoggerListener extends AbstractListener implements LoggerListener
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $loggerMethod = 'info';

    /**
     * Constructor.
     *
     * @param Logger $logger
     * @param string $loggerMethod
     */
    public function __construct(
        Logger $logger,
        $loggerMethod = 'info'
    ) {
        $this->logger = $logger;
        $this->loggerMethod = $loggerMethod;
    }


    /**
     * __invoke
     *
     * @param Event $event
     *
     * @return bool
     */
    public function __invoke(Event $event)
    {
        $method = $this->loggerMethod;
        $this->logger->$method(
            $this->getMessage($event)
        );

        return false;
    }

    /**
     * getMessage
     *
     * @param Event $event
     *
     * @return string
     */
    abstract protected function getMessage(Event $event);
}
