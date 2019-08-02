<?php

namespace RcmUser\Log\Event\AclDataService;

use RcmUser\Acl\Service\AclDataService;

/**
 * Class DeleteAclRuleFailListener
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class DeleteAclRuleFailListener extends AbstractAclDataServiceListener
{
    /**
     * @var string
     */
    protected $event = AclDataService::EVENT_DELETE_ACL_RULE_FAIL;
}
