<?php

namespace RcmAdmin\Factory;

use Interop\Container\ContainerInterface;
use RcmAdmin\Controller\AdminPanelController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AdminPanelControllerFactory
 *
 * Admin Panel Controller
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
class AdminPanelControllerFactory
{
    /**
     * Factory for the Admin Panel Controller
     *
     * @param ContainerInterface|ServiceLocatorInterface $container
     *
     * @return AdminPanelController
     */
    public function __invoke($container)
    {
        $config = $container->get('config');

        $adminPanelConfig = [];

        if (!empty($config['rcmAdmin']['adminPanel'])
            && is_array($config['rcmAdmin']['adminPanel'])
        ) {
            $adminPanelConfig = $config['rcmAdmin']['adminPanel'];
        }

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $container->get(\Rcm\Service\CurrentSite::class);

        /** @var \Rcm\Acl\CmsPermissionChecks $cmsPermissionChecks */
        $cmsPermissionChecks = $container->get(\Rcm\Acl\CmsPermissionChecks::class);

        return new AdminPanelController(
            $adminPanelConfig,
            $currentSite,
            $cmsPermissionChecks
        );
    }
}
