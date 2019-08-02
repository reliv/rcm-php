<?php

namespace RcmUser\Log\Event\AclDataService;

use RcmUser\Acl\Service\AclDataService;
use RcmUser\Log\Event\AbstractLoggerListener;
use RcmUser\Log\Event\LoggerListener;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;
use RcmUser\Service\Server;
use Zend\EventManager\Event;

/**
 * Class AbstractAclDataServiceListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractAclDataServiceListener extends AbstractLoggerListener implements LoggerListener
{
    /**
     * @var string|array
     */
    protected $identifier = AclDataService::EVENT_IDENTIFIER;

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
     */
    public function __construct(
        Logger $logger,
        RcmUserService $rcmUserService
    ) {
        $this->rcmUserService = $rcmUserService;
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

        $data['aclRule'] = $event->getParam('aclRule');
        $data['requestIp'] = Server::getRemoteIpAddress();
        $data['result'] = $event->getParam('result');
        $data['sessionId'] = Server::getSessionId();
        $data['currentUser'] = $this->rcmUserService->getCurrentUser();

        $message = json_encode($data, $this->jsonOptions);

        return $message;
    }
}
