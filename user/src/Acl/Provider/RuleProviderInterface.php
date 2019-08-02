<?php

namespace RcmUser\Acl\Provider;

use RcmUser\Acl\Entity\AclRule;

/**
 * @todo Future Use
 * Class RuleProviderInterface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface RuleProviderInterface
{
    /**
     * hasRule
     *
     * @param string $ruleId
     *
     * @return bool
     */
    public function hasRule($ruleId);

    /**
     * getRule
     *
     * @param $ruleId
     *
     * @return AclRule|null
     */
    public function getRule($ruleId);

    /**
     * getRules All
     *
     * @return array AclRules
     */
    public function getRules();

    /**
     * getRulesByResources
     *
     * @param array $resources Array of Resources to lookup
     *
     * @return array AclRules
     */
    public function getRulesByResources(array $resources);

    /**
     * getRulesByResource
     *
     * @param string $resourceId $resourceId
     *
     * @return array AclRules
     */
    public function getRulesByResource($resourceId);

    /**
     * getRulesByResourcePrivilege
     *
     * @param $resourceId
     * @param $privilege
     *
     * @return array AclRules
     */
    public function getRulesByResourcePrivilege($resourceId, $privilege);

    /**
     * getRulesByRole
     *
     * @param string $roleId roleId
     *
     * @return array AclRules
     */
    public function getRulesByRole($roleId);
}
