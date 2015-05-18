<?php
/**
 * Service Factory for the Admin Page Controller
 *
 * This file contains the factory needed to generate a Admin Page Controller.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmAdmin\Factory;

use RcmAdmin\Controller\PageController;
use Zend\Di\ServiceLocator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Admin Page Controller
 *
 * Factory for the Admin Page Controller.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class PageControllerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $controllerManager Zend Controller Manager
     *
     * @return NewPageController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerMgr For IDE */
        $controllerMgr = $controllerManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerMgr->getServiceLocator();

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get('Rcm\Service\CurrentSite');

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        return new PageController(
            $currentSite,
            $pageRepo
        );
    }
}
