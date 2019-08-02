<?php

namespace RcmUser\Acl\Entity;

/**
 * Class RootAclResource
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
class RootAclResource extends AclResource
{
    /**
     * Unique id of Resource
     *
     * @var string
     */
    protected $resourceId = 'root';

    /**
     * @var string $providerId The resource provider Id
     */
    protected $providerId = 'root';

    /**
     * @var string $parentResourceId
     */
    protected $parentResourceId = null;

    /**
     * @var AclResource $parentResource
     */
    protected $parentResource = null;

    /**
     * @var array $privileges
     */
    protected $privileges
        = [
            'read',
            'update',
            'create',
            'delete',
            'execute',
        ];

    /**
     * @var string $name
     */
    protected $name = 'Root Resource';

    /**
     * @var string $description
     */
    protected $description
        = 'This is the lowest level resource. Access to this will allow access to all resources.';

    /**
     * @over-ride
     * RootResourceProvider constructor.
     */
    public function __construct()
    {
        // over-ride
    }
}
