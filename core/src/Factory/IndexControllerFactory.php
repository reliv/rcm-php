<?php

namespace Rcm\Factory;

use Rcm\Controller\IndexController;
use Rcm\Page\Renderer\PageRendererBc;
use Rcm\Renderer\RenderViewModelWithChildren;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @deprecated  PLEASE USE CmsController
 * Service Factory for the Index Controller
 *
 * Factory for the Index Controller.
 *
 * @category    Reliv
 * @package     Rcm
 * @author      Westin Shafer <wshafer@relivinc.com>
 * @copyright   2012 Reliv International
 * @license     License.txt New BSD License
 * @version     Release: 1.0
 * @link        https://github.com/reliv
 *
 */
class IndexControllerFactory
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $controllerManager Zend Controler Manager
     *
     * @return IndexController
     */
    public function __invoke($controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerMgr For IDE */
        $controllerMgr = $controllerManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $container = $controllerMgr->getServiceLocator();

        /** @var PageRendererBc $pageRendererer */
        $pageRenderer = $container->get(PageRendererBc::class);

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $container->get(\Rcm\Service\CurrentSite::class);

        return new IndexController(
            $pageRenderer,
            $currentSite,
            $container->get(RenderViewModelWithChildren::class)
        );
    }
}
