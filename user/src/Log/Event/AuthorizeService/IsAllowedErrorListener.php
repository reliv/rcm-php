<?php

namespace RcmUser\Log\Event\AuthorizeService;

use RcmUser\Acl\Service\AuthorizeService;
use Zend\EventManager\Event;

/**
 * Class IsAllowedErrorListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedErrorListener extends AbstractAuthorizeServiceListener
{
    /**
     * @var string
     */
    protected $event = AuthorizeService::EVENT_IS_ALLOWED_ERROR;

    /**
     * __invoke
     *
     * @param Event $event
     *
     * @return bool
     */
    public function __invoke(Event $event)
    {
        $this->logger->err(
            $this->getMessage($event)
        );

        return false;
    }
}
