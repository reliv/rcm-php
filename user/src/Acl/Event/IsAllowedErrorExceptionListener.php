<?php

namespace RcmUser\Acl\Event;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Event\AbstractListener;
use RcmUser\Exception\RcmUserException;
use Zend\EventManager\Event;

/**
 * Class IsAllowedErrorExceptionListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedErrorExceptionListener extends AbstractListener implements AclListener
{
    /**
     * @var string|array
     */
    protected $identifier = AuthorizeService::EVENT_IDENTIFIER;

    /**
     * @var string
     */
    protected $event = AuthorizeService::EVENT_IS_ALLOWED_ERROR;

    /**
     * __invoke
     *
     * @param Event $event
     *
     * @return void
     * @throws RcmUserException
     */
    public function __invoke(Event $event)
    {
        $params = $event->getParams();

        throw new RcmUserException(json_encode($params, JSON_PRETTY_PRINT), 401);

        // break event chain
        // return true;
    }
}
