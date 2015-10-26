<?php

namespace Rcm\Service;

use RcmUser\Service\RcmUserService;

/**
 * Class RcmIsAllowed
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUser
{
    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * @param RcmUserService $rcmUserService
     */
    public function __construct(
        RcmUserService $rcmUserService
    ) {
        $this->rcmUserService = $rcmUserService;
    }

    /**
     * isAllowed
     *
     * @param        $resourceId
     * @param null   $privilege
     * @param string $providerId
     *
     * @return mixed
     */
    public function isAllowed(
        $resourceId,
        $privilege = null,
        $providerId = 'Rcm\Acl\ResourceProvider'
    ) {
        return $this->rcmUserService->isAllowed(
            $resourceId,
            $privilege,
            $providerId
        );
    }

}
