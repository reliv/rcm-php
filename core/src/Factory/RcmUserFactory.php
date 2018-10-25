<?php

namespace Rcm\Factory;

use Rcm\Controller\Plugin\RcmIsAllowed;
use Rcm\Service\RcmUser;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RcmUserFactory
 *
 * RcmUserFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator mgr
     *
     * @return mixed|RcmIsAllowed
     */
    public function __invoke($serviceLocator)
    {
        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $serviceLocator->get(
            \RcmUser\Service\RcmUserService::class
        );

        $service = new RcmUser($rcmUserService);

        return $service;
    }
}
