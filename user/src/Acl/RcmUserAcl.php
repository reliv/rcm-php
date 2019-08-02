<?php

namespace RcmUser\Acl;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Class RcmUserAcl
 *
 * RcmUserAcl @todo
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserAcl extends Acl
{
    /**
     * @var string
     */
    const ALLOWED = 'allowed';
    /**
     * @var string
     */
    const NOTALLOWED = 'not allowed';
    /**
     * @var string
     */
    const DENIED = 'denied';

    /**
     * @var RoleInterface $accessRole
     */
    protected $accessRole = null;

    /**
     * @var Resource $accessResource
     */
    protected $accessResource = null;

    /**
     * @var string $accessPrivilege
     */
    protected $accessPrivilege = null;

    /**
     * getAccess
     *
     * @param RoleInterface|string     $role      role
     * @param ResourceInterface|string $resource  resource
     * @param string                   $privilege privilege
     *
     * @return string
     *
     * public function getAccess($role = null, $resource = null, $privilege = null)
     * {
     * // reset role & resource to null
     * $this->accessRole = null;
     * $this->accessResource = null;
     * $this->accessPrivilege = null;
     *
     * if (null !== $role) {
     * // keep track of originally called role
     * $this->accessRole = $role;
     * $role = $this->getRoleRegistry()->get($role);
     * if (!$this->accessRole instanceof RoleInterface) {
     * $this->accessRole = $role;
     * }
     * }
     *
     * if (null !== $resource) {
     * // keep track of originally called resource
     * $this->accessResource = $resource;
     * $resource = $this->getResource($resource);
     * if (!$this->accessResource instanceof ResourceInterface) {
     * $this->accessResource = $resource;
     * }
     * }
     *
     * if (null === $privilege) {
     * // query on all privileges
     * do {
     * // depth-first search on $role if it is not 'allRoles' pseudo-parent
     * if (null !== $role
     * && null !== ($result = $this->roleDFSAllPrivileges(
     * $role,
     * $resource,
     * $privilege
     * ))
     * ) {
     * echo "1:";
     * return $result;
     * }
     *
     * // look for rule on 'allRoles' pseudo-parent
     * if (null !== ($rules = $this->getRules($resource, null))) {
     * foreach ($rules['byPrivilegeId'] as $privilege => $rule) {
     * if (Acl::TYPE_DENY === (
     * $ruleTypeOnePrivilege = $this->getRuleType(
     * $resource,
     * null,
     * $privilege
     * ))
     * ) {
     * echo "\n2: " .var_export($ruleTypeOnePrivilege, true);
     * return $this->getAccessType($ruleTypeAllPrivileges);
     * }
     * }
     * if (null !== ($ruleTypeAllPrivileges = $this->getRuleType(
     * $resource, null, null
     * ))
     * ) {
     * echo "\n3: " .
     * var_export(Acl::TYPE_ALLOW === $ruleTypeAllPrivileges, true);
     * return $this->getAccessType($ruleTypeAllPrivileges);
     * }
     * }
     *
     * // try next Resource
     * $resource = $this->resources[$resource->getResourceId()]['parent'];
     *
     * } while (true); // loop terminates at 'allResources' pseudo-parent
     * } else {
     * $this->accessPrivilege = $privilege;
     * // query on one privilege
     * do {
     * // depth-first search on $role if it is not 'allRoles' pseudo-parent
     * if (null !== $role
     * && null !== ($result = $this->roleDFSOnePrivilege(
     * $role,
     * $resource,
     * $privilege
     * ))
     * ) {
     * echo "4:";
     * return $result;
     * }
     *
     * // look for rule on 'allRoles' pseudo-parent
     * if (null !== (
     * $ruleType = $this->getRuleType($resource, null, $privilege))
     * ) {
     * echo "5:";
     * return Acl::TYPE_ALLOW === $ruleType;
     * } elseif (null !== ($ruleTypeAllPrivileges = $this->getRuleType(
     * $resource,
     * null,
     * null
     * ))
     * ) {
     * $result = Acl::TYPE_ALLOW === $ruleTypeAllPrivileges;
     * if ($result || null === $resource) {
     * echo "6:";
     * return $result;
     * }
     * }
     *
     * // try next Resource
     * $resource = $this->resources[$resource->getResourceId()]['parent'];
     *
     * } while (true); // loop terminates at 'allResources' pseudo-parent
     * }
     * }
     */
    /**
     * getAccessType
     *
     * @param string $ruleType ruleType
     *
     * @return string
     */
    public function getAccessType($ruleType)
    {
        if (Acl::TYPE_DENY === $ruleType) {
            return self::DENIED;
        }
        if (Acl::TYPE_ALLOW === $ruleType) {
            return self::ALLOWED;
        }

        return self::NOTALLOWED;
    }
}
