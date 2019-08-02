<?php

namespace RcmUser\Log\Event\AuthorizeService;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Log\Event\AbstractLoggerListener;
use RcmUser\Log\Event\LoggerListener;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;
use RcmUser\Service\Server;
use Zend\EventManager\Event;

/**
 * Class AbstractAuthorizeServiceListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractAuthorizeServiceListener extends AbstractLoggerListener implements LoggerListener
{
    /**
     * @var string|array
     */
    protected $identifier = AuthorizeService::EVENT_IDENTIFIER;

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

        $data['definedRoles'] = $event->getParam('definedRoles');
        $data['error'] = $event->getParam('error');
        $data['privilege'] = $event->getParam('privilege');
        $data['providerId'] = $event->getParam('providerId');
        $data['requestIp'] = Server::getRemoteIpAddress();
        $data['resourceId'] = $event->getParam('resourceId');
        $data['result'] = $event->getParam('result');
        $data['sessionId'] = Server::getSessionId();
        $data['user'] = $event->getParam('user');
        $data['userRoles'] = $event->getParam('userRoles');
        $data['currentUser'] = $this->rcmUserService->getCurrentUser();

        $message = json_encode($data, $this->jsonOptions);

        return $message;
    }
}
