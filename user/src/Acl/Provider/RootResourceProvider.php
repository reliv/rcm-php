<?php

namespace RcmUser\Acl\Provider;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Entity\RootAclResource;

/**
 * Class RootResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RootResourceProvider implements ResourceProviderInterface
{
    /**
     * @var string $providerId
     */
    protected $providerId = 'root';

    /**
     * @var array $resources
     */
    protected $resources = [];

    /**
     * RootResourceProvider constructor.
     */
    public function __construct(RootAclResource $rootAclResource)
    {
        $this->resources[$this->providerId] = $rootAclResource;
    }

    /**
     * getProviderId
     *
     * @return string
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * getResources
     * Return an array of resources and privileges
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
     * Return the requested resource or null if not found
     * Can be used to return resources dynamically at run-time
     *
     * @param string $resourceId resourceId
     *
     * @return AclResource|array|null
     */
    public function getResource($resourceId)
    {
        if (isset($this->resources[$resourceId])) {
            return $this->resources[$resourceId];
        }

        return null;
    }

    /**
     * hasResource
     *
     * @param string $resourceId
     *
     * @return bool
     */
    public function hasResource($resourceId)
    {
        $resource = $this->getResource($resourceId);

        return ($resource !== null);
    }
}
