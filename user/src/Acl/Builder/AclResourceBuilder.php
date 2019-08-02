<?php

namespace RcmUser\Acl\Builder;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Exception\RcmUserException;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class AclResourceBuilder
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
class AclResourceBuilder
{
    /**
     * @var AclResource
     */
    protected $rootResource;

    /**
     * AclResourceBuilder constructor.
     *
     * @param AclResource $rootResource
     */
    public function __construct(
        AclResource $rootResource
    ) {
        $this->rootResource = $rootResource;
    }

    /**
     * build
     *
     * @param $resourceData
     *
     * @return null|AclResource
     * @throws RcmUserException
     */
    public function build(
        $resourceData
    ) {
        $resource = null;

        // Is AclResource
        if ($resourceData instanceof AclResource) {
            $resource = $resourceData;
        }

        // Is ResourceInterface
        if (!$resourceData instanceof AclResource
            && $resourceData instanceof ResourceInterface
        ) {
            $resource = new AclResource(
                $resourceData->getResourceId()
            );
        }

        // Is array
        if (is_array($resourceData)) {
            $resource = new AclResource(
                $resourceData['resourceId']
            );
            $resource->populate($resourceData);
        }

        // Is resourceId
        if (is_string($resourceData)) {
            $resource = new AclResource(
                $resourceData
            );
        }

        if ($resource === null) {
            throw new RcmUserException(
                'Resource is not valid: ' . var_export(
                    $resourceData,
                    true
                )
            );
        }

        $resource = $this->buildParent($resource);

        return $resource;
    }

    /**
     * buildParent
     *
     * @param AclResource $resource
     *
     * @return AclResource
     * @throws RcmUserException
     */
    public function buildParent(AclResource $resource)
    {
        $parentResourceId = $resource->getParentResourceId();

        // root parent must be null
        if ($resource->getResourceId() == $this->rootResource->getResourceId()) {
            $resource->setParentResourceId(
                null
            );
            return $resource;
        }

        if (empty($parentResourceId)) {
            $resource->setParentResourceId(
                $this->rootResource->getResourceId()
            );
        }

        return $resource;
    }
}
