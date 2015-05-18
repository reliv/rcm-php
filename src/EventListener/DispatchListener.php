<?php
/**
 * Dispatch Listener for RcmAdmin
 *
 * This file contains the Dispatch Listener for RcmAdmin.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdmin\EventListener;

use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use RcmAdmin\Controller\AdminPanelController;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

/**
 * Dispatch Listener for RcmAdmin
 *
 * Dispatch Listener for RcmAdmin.  This will add the admin panel to users who
 * are allowed to admin the site.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class DispatchListener
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /**
     * Constructor
     *
     * @param AdminPanelController $adminPanelController Admin Panel Controller
     */
    public function __construct(
        ServiceLocatorInterface $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get Admin Panel
     *
     * @param MvcEvent $event Zend MVC Event
     *
     * @return void
     */
    public function getAdminPanel(MvcEvent $event)
    {
        $matchRoute = $event->getRouteMatch();

        if (empty($matchRoute)) {
            return null;
        }

        /** @var \RcmAdmin\Controller\AdminPanelController $adminPanelController */
        $adminPanelController
            = $this->serviceLocator->get('RcmAdmin\Controller\AdminPanelController');

        $adminPanelController->setEvent($event);

        $adminWrapper = $adminPanelController->getAdminWrapperAction();

        if (!$adminWrapper instanceof ViewModel) {
            return;
        }

        /** @var \Zend\View\Model\ViewModel $viewModel */
        $layout = $event->getViewModel();
        $layout->addChild($adminWrapper, 'rcmAdminPanel');
    }
}
