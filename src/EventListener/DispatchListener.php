<?php
/**
 * RCM Dispatch Listener
 *
 * Dispatch Listener for Zend Event "dispatch"
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\EventListener;

use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RCM Dispatch Listener
 *
 * This Dispatch listener will setup the current Zend Layout, Site Title, and
 * site favicon base on the data returned from CMS site manager.  Setting up the
 * site layout in this manner allows for the CMS to wrap itself around normal ZF2
 * modules.  Also making the CMS more ZF2 friendly.
 *
 * @category  Reliv
 * @package   Rcm
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
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * getLayoutManager
     *
     * @return \Rcm\Service\LayoutManager
     */
    protected function getLayoutManager() {

        return $this->serviceLocator->get('Rcm\Service\LayoutManager');
    }

    /**
     * getCurrentSite
     *
     * @return \Rcm\Entity\Site
     */
    protected function getCurrentSite()
    {
        return $this->serviceLocator->get('Rcm\Service\CurrentSite');
    }

    /**
     * getViewHelperManager
     *
     * @return \Zend\View\HelperPluginManager
     */
    protected function getViewHelperManager()
    {
        return $this->serviceLocator->get('viewHelperManager');
    }

    /**
     * getSiteLayoutTemplate
     *
     * @return string
     */
    protected function getSiteLayoutTemplate()
    {
        return $this->getLayoutManager()->getSiteLayout($this->getCurrentSite());
    }

    /**
     * Set Site Layout
     *
     * @param MvcEvent $event Zend MVC Event object
     *
     * @return null
     */
    public function setSiteLayout(MvcEvent $event)
    {
        /** @var \Zend\View\Model\ViewModel $viewModel */
        $viewModel = $event->getViewModel();

        /* Add on for non CMS pages */
        $fakePage = new Page();
        $fakeRevision = new Revision();
        $fakePage->setCurrentRevision($fakeRevision);

        $currentSite = $this->getCurrentSite();
        $viewHelperManager = $this->getViewHelperManager();

        $viewModel->setVariable('page', $fakePage);
        $viewModel->setVariable('site', $currentSite);

        $template = $this->getSiteLayoutTemplate();

        $viewModel->setTemplate('layout/' . $template);

        //Inject Meta Tags
        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $viewHelperManager->get('headLink');

        /** @var \Zend\View\Helper\BasePath $basePath */
        $basePath = $viewHelperManager->get('basePath');

        /** @var \Zend\View\Helper\HeadTitle $headTitle */
        $headTitle = $viewHelperManager->get('headTitle');

        $favicon = $currentSite->getFavIcon();
        $siteTitle = $currentSite->getSiteTitle();

        //Add Favicon for site
        if (!empty($favicon)) {
            $headLink(
                [
                    'rel' => 'shortcut icon',
                    'type' => 'image/vnd.microsoft.icon',
                    'href' => $basePath() . $favicon,
                ]
            );
        }

        if (!empty($siteTitle)) {
            $headTitle($siteTitle);
        }

        $headTitle()->setSeparator(' - ');

        return null;
    }
}
