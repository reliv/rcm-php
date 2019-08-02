<?php

namespace RcmUser\Log\Event\AclDataService;

use RcmUser\Acl\Service\AclDataService;

/**
 * Class CreateAclRoleSuccessListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CreateAclRoleSuccessListener extends AbstractAclDataServiceListener
{
    /**
     * @var string
     */
    protected $event = AclDataService::EVENT_CREATE_ACL_ROLE_SUCCESS;
}
