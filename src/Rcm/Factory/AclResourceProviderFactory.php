<?php
/**
 * Service Factory for the Rcm Cache
 *
 * This file contains the factory needed to generate an Rcm Cache.
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

use Rcm\Acl\ResourceProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for Rcm Cache
 *
 * Factory for Rcm Cache.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class AclResourceProviderFactory implements FactoryInterface
{

    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return ResourceProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        $aclConfig = array();

        if (!empty($config['Rcm']['Acl'])) {
            $aclConfig = $config['Rcm']['Acl'];
        }

        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager = $serviceLocator->get('\Rcm\Service\SiteManager');

        /** @var \Rcm\Service\PageManager $pageManager */
        $pageManager = $serviceLocator->get('\Rcm\Service\PageManager');

        /** @var \Rcm\Service\PluginManager $pluginManager */
        $pluginManager = $serviceLocator->get('\Rcm\Service\PluginManager');

        return new ResourceProvider(
            $aclConfig,
            $siteManager,
            $pageManager,
            $pluginManager
        );
    }
}
