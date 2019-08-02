<?php

namespace RcmUser\Provider;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Provider\ResourceProvider;

/**
 * RcmUserAclResourceProvider
 *
 * RcmUserAclResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserAclResourceProvider extends ResourceProvider
{
    /**
     * @var string PROVIDER_ID This needs to be the same as the config
     */
    const PROVIDER_ID = 'RcmUser';

    /**
     * @var string RESOURCE_ID_ROOT
     */
    const RESOURCE_ID_ROOT = 'rcmuser';

    /**
     * @var string RESOURCE_ID_ACL
     */
    const RESOURCE_ID_ACL = 'rcmuser-acl-administration';

    /**
     * @var string RESOURCE_ID_USER
     */
    const RESOURCE_ID_USER = 'rcmuser-user-administration';

    /**
     * default resources  - rcm user needs these,
     * however descriptions added on construct in the factory
     *
     * @var array $rcmResources
     */
    protected $resources = [];

    /**
     * RcmUserAclResourceProvider constructor.
     */
    public function __construct()
    {
        $this->buildResources();
    }

    /**
     * getResources (ALL resources)
     * Return a multi-dimensional array of resources and privileges
     * containing ALL possible resources including run-time resources
     *
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * getResource
     * Return the requested resource
     * Can be used to return resources dynamically.
     *
     * @param string $resourceId resourceId
     *
     * @return array
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function getResource($resourceId)
    {
        return parent::getResource($resourceId);
    }

    /**
     * buildResources - build static resources
     *
     * @return void
     */
    protected function buildResources()
    {
        $privileges = [
            'read',
            'update',
            'create',
            'delete',
        ];

        $userPrivileges = [
            'read',
            'update',
            'create',
            'delete',
            'update_credentials',
        ];

        /* parent resource */
        $resource = new AclResource(self::RESOURCE_ID_ROOT);
        $resource->setName(
            'RCM User'
        );
        $resource->setDescription(
            'All RCM user access.'
        );
        $resource->setPrivileges(
            $privileges
        );
        $this->resources[self::RESOURCE_ID_ROOT] = $resource;

        /* user edit */
        $resource = new AclResource(
            self::RESOURCE_ID_USER,
            self::RESOURCE_ID_ROOT,
            $userPrivileges
        );
        $resource->setName(
            'User Administration'
        );
        $resource->setDescription(
            'Allows the editing of user data.'
        );
        $this->resources[self::RESOURCE_ID_USER] = $resource;

        /* access and roles */
        $resource = new AclResource(
            self::RESOURCE_ID_ACL,
            self::RESOURCE_ID_ROOT,
            $privileges
        );
        $resource->setName(
            'Role and Access Administration'
        );
        $resource->setDescription(
            'Allows the editing of user access and role data.'
        );

        $resource->setParentResourceId(
            self::RESOURCE_ID_ROOT
        );
        $this->resources[self::RESOURCE_ID_ACL] = $resource;
    }
}
