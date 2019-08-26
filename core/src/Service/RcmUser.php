<?php

namespace Rcm\Service;

use RcmUser\Service\RcmUserService;

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
        $providerId = \Rcm\Acl\ResourceProvider::class
    ) {
        return $this->rcmUserService->isAllowed(
            $resourceId,
            $privilege,
            $providerId
        );
    }
}
