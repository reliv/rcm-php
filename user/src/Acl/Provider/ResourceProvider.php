<?php

namespace RcmUser\Acl\Provider;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Exception\RcmUserAclException;

/**
 * class ResourceProvider
 *
 * ResourceProvider
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Provider
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ResourceProvider implements ResourceProviderInterface
{
    /**
     * @var string $providerId
     */
    protected $providerId = null;

    /**
     * @var array $resources
     */
    protected $resources = [];

    /**
     * __construct
     *
     * @param array $resources resources
     */
    public function __construct($resources)
    {
        if (is_array($resources)) {
            $this->resources = $resources;
        }
    }

    /**
     * setProviderId
     *
     * @param $providerId
     *
     * @return void
     * @throws RcmUserAclException
     */
    public function setProviderId($providerId)
    {
        if ($providerId === null) {
            throw new RcmUserAclException('Provider ID cannot be null');
        }
        $this->providerId = $providerId;
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
