<?php

namespace RcmAdmin\Factory;

use RcmAdmin\Controller\AdminPanelController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AdminPanelControllerFactory
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Factory
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AdminPanelControllerFactory implements FactoryInterface
{

    /**
     * Factory for the Admin Panel Controller
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return AdminPanelController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        $adminPanelConfig = [];

        if (!empty($config['rcmAdmin']['adminPanel'])
            && is_array($config['rcmAdmin']['adminPanel'])
        ) {
            $adminPanelConfig = $config['rcmAdmin']['adminPanel'];
        }

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');

        /** @var \Rcm\Acl\CmsPermissionChecks $cmsPermissionChecks */
        $cmsPermissionChecks = $serviceLocator->get('Rcm\Acl\CmsPermissionsChecks');

        return new AdminPanelController(
            $adminPanelConfig,
            $currentSite,
            $cmsPermissionChecks
        );
    }
}
