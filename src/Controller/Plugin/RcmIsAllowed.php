<?php

namespace Rcm\Controller\Plugin;

use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * RcmIsAllowed
 *
 * RcmIsAllowed
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmIsAllowed extends AbstractPlugin
{
    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * RcmIsAllowed constructor.
     *
     * @param RcmUserService $rcmUserService
     */
    public function __construct(
        RcmUserService $rcmUserService
    ) {
        $this->rcmUserService = $rcmUserService;
    }

    /**
     * __invoke
     *
     * @param string $resourceId resourceId
     * @param string $privilege  privilege
     * @param string $providerId providerId
     *
     * @return bool
     */
    public function __invoke(
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
