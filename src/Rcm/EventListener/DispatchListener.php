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

use Rcm\Service\LayoutManager;
use Rcm\Service\SiteManager;
use Zend\Mvc\MvcEvent;
use Zend\View\HelperPluginManager;

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

    /** @var \Rcm\Service\LayoutManager */
    protected $layoutManager;

    /** @var \Rcm\Service\SiteManager */
    protected $siteManager;

    /** @var \Zend\View\HelperPluginManager */
    protected $viewHelperManager;

    /**
     * Constructor
     *
     * @param LayoutManager       $layoutManager     RCM Layout Manager
     * @param SiteManager         $siteManager       Rcm Site Manager
     * @param HelperPluginManager $viewHelperManager Zend Framework View Helper Mgr
     */
    public function __construct(
        LayoutManager $layoutManager,
        SiteManager $siteManager,
        HelperPluginManager $viewHelperManager
    ) {
        $this->layoutManager = $layoutManager;
        $this->siteManager = $siteManager;
        $this->viewHelperManager = $viewHelperManager;
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

        $template = $this->layoutManager->getSiteLayout();
        $viewModel->setTemplate('layout/' . $template);

        $siteInfo = $this->siteManager->getCurrentSiteInfo();

        //Inject Meta Tags
        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $this->viewHelperManager->get('headLink');

        /** @var \Zend\View\Helper\BasePath $basePath */
        $basePath = $this->viewHelperManager->get('basePath');

        /** @var \Zend\View\Helper\HeadTitle $headTitle */
        $headTitle = $this->viewHelperManager->get('headTitle');

        //Add Favicon for site
        if (!empty($siteInfo['favIcon'])) {
            $headLink(
                array(
                    'rel' => 'shortcut icon',
                    'type' => 'image/vnd.microsoft.icon',
                    'href' => $basePath() . $siteInfo['favIcon'],
                )
            );
        }

        if (!empty($siteInfo['siteTitle'])) {
            $headTitle($siteInfo['siteTitle']);
        }

        $headTitle()->setSeparator(' - ');

        return null;
    }
}
