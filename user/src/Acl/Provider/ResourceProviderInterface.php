<?php

namespace RcmUser\Acl\Provider;

use RcmUser\Acl\Entity\AclResource;

/**
 * Interface ResourceProviderInterface
 *
 * ResourceProviderInterface Interface
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
interface ResourceProviderInterface
{
    /**
     * getProviderId
     *
     * @return string
     */
    public function getProviderId();

    /**
     * getResources (ALL resources) {"resourceId": "{AclResource}"}
     * Return an array of resources and privileges indexed by resourceId
     * containing ALL possible resources including run-time resources
     *
     * @return array
     */
    public function getResources();

    /**
     * getResource
     * Return the requested resource or null if not found
     * Can be used to return resources dynamically at run-time
     *
     * @param string $resourceId $resourceId
     *
     * @return AclResource|null
     */
    public function getResource($resourceId);

    /**
     * hasResource
     *
     * @param string $resourceId
     *
     * @return bool
     */
    public function hasResource($resourceId);
}
