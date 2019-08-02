<?php

namespace RcmUser\Log\Event\AuthorizeService;

use RcmUser\Acl\Service\AuthorizeService;

/**
 * Class IsAllowedFalseListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedFalseListener extends AbstractAuthorizeServiceListener
{
    /**
     * @var string
     */
    protected $event = AuthorizeService::EVENT_IS_ALLOWED_FALSE;
}
