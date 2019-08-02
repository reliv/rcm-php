<?php

namespace RcmUser\Acl\Provider;

use RcmUser\Acl\Builder\AclResourceBuilder;
use RcmUser\Acl\Cache\ResourceCache;
use RcmUser\Acl\Entity\AclResource;

/**
 * Class CompositeResourceProvider
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
class CompositeResourceProvider implements ResourceProviderInterface
{
    /**
     * @var array
     */
    protected $resourceProviders = [];

    /**
     * @var ResourceCache
     */
    protected $cache;

    /**
     * @var AclResourceBuilder
     */
    protected $aclResourceBuilder;

    /**
     * CompositeResourceProvider constructor.
     *
     * @param ResourceCache      $cache
     * @param AclResourceBuilder $aclResourceBuilder
     */
    public function __construct(
        ResourceCache $cache,
        AclResourceBuilder $aclResourceBuilder
    ) {
        $this->cache = $cache;
        $this->aclResourceBuilder = $aclResourceBuilder;
    }

    /**
     * add
     *
     * @param ResourceProviderInterface $resourceProvider
     *
     * @return void
     */
    public function add(ResourceProviderInterface $resourceProvider)
    {
        $this->resourceProviders[$resourceProvider->getProviderId()]
            = $resourceProvider;
    }

    /**
     * getProviderId
     *
     * @return string
     */
    public function getProviderId()
    {
        return \RcmUser\Acl\Provider\CompositeResourceProvider::class;
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
        $allResources = [];

        /** @var ResourceProviderInterface $resourceProvider */
        foreach ($this->resourceProviders as $resourceProvider) {
            $providerId = $resourceProvider->getProviderId();
            $resources = $this->cache->getProviderResources($providerId);
            if ($resources === null) {
                $resources = $resourceProvider->getResources();
                foreach ($resources as $key => $resourceData) {
                    $resource = $this->aclResourceBuilder->build($resourceData);
                    // @todo Not required
                    $resource->setProviderId($providerId);
                    $this->cache->set($resource);
                    $resources[$key] = $resource;
                }
                $this->cache->setProviderResources($providerId, $resources);
            }

            $allResources = array_merge($allResources, $resources);
        }

        return $allResources;
    }

    /**
     * getResource
     * Return the requested resource or null if not found
     * Can be used to return resources dynamically at run-time
     *
     * @param string $resourceId $resourceId
     *
     * @return AclResource|null
     */
    public function getResource($resourceId)
    {
        /** @var AclResource $resource */
        $resourceData = $this->cache->get($resourceId);
        $resource = null;

        if ($resourceData !== null) {
            $resource = $this->aclResourceBuilder->build($resourceData);

            return $resource;
        }

        /** @var ResourceProviderInterface $resourceProvider */
        foreach ($this->resourceProviders as $resourceProvider) {
            $hasResource = $resourceProvider->hasResource($resourceId);
            if ($hasResource) {
                $resourceData = $resourceProvider->getResource($resourceId);
                /** @var AclResource $resource */
                $resource = $this->aclResourceBuilder->build($resourceData);
                // @todo Not required
                $resource->setProviderId($resourceProvider->getProviderId());
                $this->cache->set($resource);

                return $resource;
            }
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
