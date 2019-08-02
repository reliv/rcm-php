<?php

namespace RcmUser\Acl\Service;

use RcmUser\Acl\Builder\AclResourceStackBuilder;
use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Provider\ResourceProviderInterface;

/**
 * Class AclResourceNsArrayService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   AclResource
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclResourceNsArrayService
{
    /**
     * @var AclResourceStackBuilder
     */
    protected $aclResourceStackBuilder;

    /**
     * @var ResourceProviderInterface
     */
    protected $resourceProvider;

    /**
     * @var string
     */
    protected $defaultNamespaceSeparator = '/';

    /**
     * AclResourceNsArrayBuilder constructor.
     *
     * @param ResourceProviderInterface $resourceProvider
     * @param AclResourceStackBuilder   $aclResourceStackBuilder
     */
    public function __construct(
        ResourceProviderInterface $resourceProvider,
        AclResourceStackBuilder $aclResourceStackBuilder
    ) {
        $this->resourceProvider = $resourceProvider;
        $this->aclResourceStackBuilder = $aclResourceStackBuilder;
    }

    /**
     * getResourcesWithNamespace
     *
     * @param null $namespaceSeparator
     *
     * @return array
     */
    public function getResourcesWithNamespace(
        $namespaceSeparator = null
    ) {
        $aclResources = [];
        $resources = $this->getNamespacedResources(
            $namespaceSeparator
        );
        /**
         * @var             $ns
         * @var AclResource $resource
         */
        foreach ($resources as $ns => $resource) {
            $resourceId = $resource->getResourceId();
            $aclResources[$resourceId] = $this->getNsModel($resource, $ns);
        }

        return $aclResources;
    }

    /**
     * getResourceWithNamespace
     *
     * @param string $resourceId
     * @param null   $namespaceSeparator
     *
     * @return array
     */
    public function getResourceWithNamespace(
        $resourceId,
        $namespaceSeparator = null
    ) {
        $resource = $this->resourceProvider->getResource($resourceId);

        $resourceTree = $this->aclResourceStackBuilder->build(
            $resource
        );

        $ns = $this->createNamespaceId(
            $resource,
            $resourceTree,
            $namespaceSeparator
        );

        return $this->getNsModel($resource, $ns);
    }

    /**
     * getNamespacedResources
     *
     * @param null $namespaceSeparator
     *
     * @return array
     */
    public function getNamespacedResources(
        $namespaceSeparator = null
    ) {
        $aclResources = [];

        $resources = $this->resourceProvider->getResources();

        foreach ($resources as $resource) {
            $ns = $this->createNamespaceId(
                $resource,
                $resources,
                $namespaceSeparator
            );

            $aclResources[$ns] = $resource;
        }

        ksort($aclResources);

        return $aclResources;
    }

    /**
     * createNamespaceId
     *
     * @param AclResource $aclResource
     * @param             $aclResources
     * @param null        $namespaceSeparator
     *
     * @return string
     */
    public function createNamespaceId(
        AclResource $aclResource,
        $aclResources,
        $namespaceSeparator = null
    ) {
        $parentId = $aclResource->getParentResourceId();
        $namespace = $aclResource->getResourceId();
        if (!empty($parentId) && isset($aclResources[$parentId])) {
            $parent = $aclResources[$parentId];

            $newNamespace = $this->createNamespaceId(
                $parent,
                $aclResources,
                $namespaceSeparator
            );

            $namespace = $newNamespace . $this->getNamespaceSeparator($namespaceSeparator) . $namespace;
        }

        return $namespace;
    }

    /**
     * getNsModel
     *
     * @param AclResource $aclResource
     * @param string      $namespace
     *
     * @return array
     */
    public function getNsModel(AclResource $aclResource, $namespace)
    {
        return [
            'resource' => $aclResource,
            'resourceNs' => $namespace,
        ];
    }

    /**
     * getNamespaceSeparator
     *
     * @param string|null $namespaceSeparator
     *
     * @return null|string
     */
    protected function getNamespaceSeparator($namespaceSeparator = null)
    {
        if (empty($namespaceSeparator)) {
            return $this->defaultNamespaceSeparator;
        }

        return $namespaceSeparator;
    }
}
