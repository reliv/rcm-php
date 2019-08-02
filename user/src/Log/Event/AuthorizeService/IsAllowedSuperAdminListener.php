<?php

namespace RcmUser\Log\Event\AuthorizeService;

use RcmUser\Acl\Service\AuthorizeService;

/**
 * Class IsAllowedSuperAdminListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IsAllowedSuperAdminListener extends AbstractAuthorizeServiceListener
{
    /**
     * @var string
     */
    protected $event = AuthorizeService::EVENT_IS_ALLOWED_SUPER_ADMIN;
}
