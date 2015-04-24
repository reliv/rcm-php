<?php
/**
 * Service Factory for the RcmIsPageAllowed Helper
 *
 * This file contains the factory needed to generate a RcmIsPageAllowed Helper
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Factory;

use Rcm\Controller\Plugin\IsAdmin;
use Rcm\Controller\Plugin\IsSiteAdmin;
use Rcm\Controller\Plugin\RcmIsPageAllowed;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the RcmIsPageAllowed Helper
 *
 * This file contains the factory needed to generate the RcmIsPageAllowed Helper
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
class RcmIsPageAllowedPluginFactory implements FactoryInterface
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $mgr mgr
     *
     * @return RcmIsPageAllowed
     */
    public function createService(ServiceLocatorInterface $mgr)
    {
        $serviceLocator = $mgr->getServiceLocator();
        $cmsPermissionChecks = $serviceLocator->get(
            'Rcm\Acl\CmsPermissionsChecks'
        );

        $service = new RcmIsPageAllowed($cmsPermissionChecks);

        return $service;
    }
}
