<?php

namespace Rcm\EventListener;


use Rcm\Service\LayoutManager;
use Rcm\Service\SiteManager;
use Zend\Mvc\MvcEvent;
use Zend\View\HelperPluginManager;

class RcmDispatchListener
{

    protected $layoutManager;
    protected $siteManager;
    protected $viewHelperManager;

    public function __construct(
        LayoutManager $layoutManager,
        SiteManager $siteManager,
        HelperPluginManager $viewHelperManager
    ) {
        $this->layoutManager = $layoutManager;
        $this->siteManager = $siteManager;
        $this->viewHelperManager = $viewHelperManager;
    }

    public function setSiteLayout(MvcEvent $event)
    {
        /** @var \Zend\View\Model\ViewModel $viewModel */
        $viewModel = $event->getViewModel();

        $template = $this->layoutManager->getLayout();
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
            $headTitle()->append($siteInfo['siteTitle']);
        }


        $headTitle()->setSeparator(' - ')
            ->setAutoEscape(false);


        return;
    }

}