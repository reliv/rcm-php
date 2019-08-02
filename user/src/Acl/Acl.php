<?php

namespace RcmUser\Acl;

use RcmUser\Acl\Provider\ResourceProviderInterface;
use RcmUser\Acl\Provider\RoleProviderInterface;
use Zend\Permissions\Acl\AclInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class Acl
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Acl implements AclInterface
{
    /**
     * @var RoleProviderInterface
     */
    protected $roleProvider;

    /**
     * @var ResourceProviderInterface
     */
    protected $resourceProvider;

    /**
     * @var
     */
    protected $aclResourceBuilder;

    /**
     * Acl constructor.
     *
     * @param RoleProviderInterface     $roleProvider
     * @param ResourceProviderInterface $resourceProvider
     */
    public function __construct(
        RoleProviderInterface $roleProvider,
        ResourceProviderInterface $resourceProvider
    ) {
        $this->roleProvider = $roleProvider;
        $this->resourceProvider = $resourceProvider;
    }

    /**
     * Returns true if and only if the Resource exists in the ACL
     *
     * The $resource parameter can either be a Resource or a Resource identifier.
     *
     * @param  \Zend\Permissions\Acl\Resource\ResourceInterface|string $resource
     *
     * @return bool
     */
    public function hasResource($resource)
    {
        if (is_string($resource)) {
            return $this->resourceProvider->hasResource($resource);
        }
        if ($resource instanceof ResourceInterface) {
            return $this->resourceProvider->hasResource($resource->getResourceId());
        }

        return false;
    }

    /**
     * Returns true if and only if the Role has access to the Resource
     *
     * The $role and $resource parameters may be references to, or the string identifiers for,
     * an existing Resource and Role combination.
     *
     * If either $role or $resource is null, then the query applies to all Roles or all Resources,
     * respectively. Both may be null to query whether the ACL has a "blacklist" rule
     * (allow everything to all). By default, Zend\Permissions\Acl creates a "whitelist" rule (deny
     * everything to all), and this method would return false unless this default has
     * been overridden (i.e., by executing $acl->allow()).
     *
     * If a $privilege is not provided, then this method returns false if and only if the
     * Role is denied access to at least one privilege upon the Resource. In other words, this
     * method returns true if and only if the Role is allowed all privileges on the Resource.
     *
     * This method checks Role inheritance using a depth-first traversal of the Role registry.
     * The highest priority parent (i.e., the parent most recently added) is checked first,
     * and its respective parents are checked similarly before the lower-priority parents of
     * the Role are checked.
     *
     * @param  \Zend\Permissions\Acl\Role\RoleInterface|string         $role
     * @param  \Zend\Permissions\Acl\Resource\ResourceInterface|string $resource
     * @param  string                                                  $privilege
     *
     * @return bool
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        // @todo make this
    }
}
