<?php

namespace RcmUser\Log\Event\AclDataService;

use RcmUser\Acl\Service\AclDataService;

/**
 * Class CreateAclRoleListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateAclRoleListener extends AbstractAclDataServiceListener
{
    /**
     * @var string
     */
    protected $event = AclDataService::EVENT_CREATE_ACL_ROLE;
}
