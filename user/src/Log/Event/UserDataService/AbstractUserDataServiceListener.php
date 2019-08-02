<?php

namespace RcmUser\Log\Event\UserDataService;

use RcmUser\Log\Event\AbstractLoggerListener;
use RcmUser\Log\Event\LoggerListener;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;
use RcmUser\Service\Server;
use RcmUser\User\Service\UserDataService;
use Zend\EventManager\Event;

/**
 * Class AbstractUserDataServiceListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractUserDataServiceListener extends AbstractLoggerListener implements LoggerListener
{
    /**
     * @var string|array
     */
    protected $identifier = UserDataService::EVENT_IDENTIFIER;

    /**
     * @var int
     */
    protected $jsonOptions = JSON_PRETTY_PRINT;

    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * Constructor.
     *
     * @param Logger         $logger
     * @param RcmUserService $rcmUserService
     * @param int            $jsonOptions
     */
    public function __construct(
        Logger $logger,
        RcmUserService $rcmUserService,
        $jsonOptions = JSON_PRETTY_PRINT
    ) {
        $this->rcmUserService = $rcmUserService;
        $this->jsonOptions = $jsonOptions;
        parent::__construct($logger);
    }

    /**
     * getMessage
     *
     * @param Event $event
     *
     * @return string
     */
    protected function getMessage(Event $event)
    {
        $data = [];

        $data['event'] = $this->event;
        $data['eventIdentifier'] = $this->identifier;
        $data['existingUser'] = $event->getParam('existingUser');
        $data['options'] = $event->getParam('options');
        $data['requestUser'] = $event->getParam('requestUser');
        $data['requestIp'] = Server::getRemoteIpAddress();
        $data['responseUser'] = $event->getParam('responseUser');
        $data['result'] = $event->getParam('result');
        $data['sessionId'] = Server::getSessionId();
        $data['currentUser'] = $this->rcmUserService->getCurrentUser();
        ;

        $message = json_encode($data, $this->jsonOptions);

        return $message;
    }
}
