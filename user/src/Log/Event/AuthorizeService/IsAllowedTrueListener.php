<?php

namespace RcmUser\Log\Event\AuthorizeService;

use RcmUser\Acl\Service\AuthorizeService;

/**
 * Class IsAllowedTrueListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedTrueListener extends AbstractAuthorizeServiceListener
{
    /**
     * @var string
     */
    protected $event = AuthorizeService::EVENT_IS_ALLOWED_TRUE;
}
