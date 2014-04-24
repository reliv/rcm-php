<?php
/**
 * Service Factory for the Dispatch Listener
 *
 * This file contains the factory needed to generate a DispatchListener.
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
 * @link      http://reliv.com
 */
namespace Rcm\Factory;

use Rcm\EventListener\DispatchListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the DispatchListener
 *
 * Factory for the Dispatch Listener.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://reliv.com
 *
 */
class DispatchListenerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return DispatchListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager     = $serviceLocator->get('Rcm\Service\LayoutManager');

        /** @var \Rcm\Service\SiteManager $siteManager */
        $siteManager       = $serviceLocator->get('Rcm\Service\SiteManager');

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('viewHelperManager');

        return new DispatchListener(
            $layoutManager,
            $siteManager,
            $viewHelperManager
        );
    }
}
