<?php

namespace RcmUser\Acl\Service;

use RcmUser\Acl\Builder\AclResourceStackBuilder;
use RcmUser\Acl\Provider\ResourceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AclResourceService
 *
 * AclResourceService
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclResourceService
{
    /**
     * @var ResourceProviderInterface
     */
    protected $resourceProvider;

    /**
     * @var AclResourceStackBuilder
     */
    protected $aclResourceStackBuilder;

    /**
     * AclResourceService constructor.
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
     * getResourceLineage
     * - Get a resource and all of its parents as array of resources
     *
     * @param $resourceId
     *
     * @return array
     */
    public function getResourceLineage(
        $resourceId
    ) {
        $resource = $this->resourceProvider->getResource($resourceId);

        if ($resource === null) {
            return [];
        }

        return $this->aclResourceStackBuilder->build(
            $resource
        );
    }

    /**
     * @deprecated Use $this->getResourceLineage()
     * getResources - Get a resource and all of its parents
     *
     * @param string $resourceId resourceId
     * @param string $providerId @deprecated No Longer Required - providerId
     *
     * @return array
     */
    public function getResources(
        $resourceId,
        $providerId = null
    ) {
        return $this->getResourceLineage($resourceId);
    }

    /**
     * @deprecated Use RcmUser\Acl\Provider\ResourceProvider::class service
     * getAllResources - All resources
     * returns a list of all resources
     * This is used for displays or utilities only
     * should not be used for ACL checks
     *
     * @param bool $refresh refresh
     *
     * @return array
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function getAllResources($refresh = false)
    {
        return $this->resourceProvider->getResources();
    }
}
