<?php

namespace RcmUser\Log\Event\UserAuthenticationService;

use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\Log\Event\AbstractLoggerListener;
use RcmUser\Log\Event\LoggerListener;
use RcmUser\Log\Logger;
use RcmUser\Service\RcmUserService;
use RcmUser\Service\Server;
use Zend\Authentication\Result;
use Zend\EventManager\Event;

/**
 * Class AbstractUserAuthenticationServiceListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
abstract class AbstractUserAuthenticationServiceListener extends AbstractLoggerListener implements LoggerListener
{
    /**
     * @var string|array
     */
    protected $identifier = UserAuthenticationService::EVENT_IDENTIFIER;

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
        $data['identity'] = $event->getParam('identity');
        $data['requestIp'] = Server::getRemoteIpAddress();
        $data['result'] = $this->prepareResult(
            $event->getParam('result')
        );
        $data['sessionId'] = Server::getSessionId();
        $data['user'] = $event->getParam('user');
        $data['currentUser'] = $this->rcmUserService->getCurrentUser();

        $message = json_encode($data, $this->jsonOptions);

        return $message;
    }

    /**
     * prepareResult
     *
     * @param Result|null $result
     *
     * @return mixed
     */
    protected function prepareResult($result)
    {
        if ($result instanceof Result) {
            return [
                'code' => $result->getCode(),
                'messages' => $result->getMessages(),
                'identity' => $result->getIdentity()
            ];
        }

        return $result;
    }
}
