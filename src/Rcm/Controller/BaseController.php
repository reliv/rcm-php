<?php

/**
 * Base controller
 *
 * This is the base controller that all controllers using the content manager
 * or shopping cart will need to exend from.  This will setup the enviornment
 * needed for your controllers to find out the site information, selected
 * country and language, along with many other global properties.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

namespace Rcm\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

/**
 * Base controller
 *
 * This is the base controller that all controllers using the content manager
 * or shopping cart will need to extend from.  This will setup the enviornment
 * needed for your controllers to find out the site information, selected
 * country and language, along with many other global properties.
 *
 * @category  Reliv
 * @package   Main\Application\Controllers\Index
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class BaseController extends \Rcm\Controller\EntityMgrAwareController
{
    /**
     * @var \Rcm\Entity\Site
     */
    protected $siteInfo;
    protected $config;

    /**
     * @var \Rcm\Model\PluginManager
     */
    protected $pluginManager;

    /**
     * @var \Rcm\Entity\User
     */
    protected $loggedInUser;

    /**
     * @var \Rcm\Entity\AdminPermissions
     */
    protected $loggedInAdminPermissions;

    /** @var \Rcm\Entity\Page $page */
    protected $page;

    /**
     * @var \Zend\View\Model\ViewModel Zend View Model
     */
    protected $view;

    /**
     * @var \Zend\View\Renderer\PhpRenderer
     */
    protected $viewRenderer;

    /**
     * @param \Rcm\Model\UserManagement\UserManagerInterface $userMgr
     * @param \Rcm\Model\PluginManager                 $pluginManager
     * @param \Doctrine\ORM\EntityManager              $entityMgr
     * @param \Zend\View\Renderer\PhpRenderer          $viewRenderer
     * @param array                                    $config
     */
    function __construct(
        \Rcm\Model\UserManagement\UserManagerInterface $userMgr,
        \Rcm\Model\PluginManager $pluginManager,
        EntityManager $entityMgr,
        \Zend\View\Renderer\PhpRenderer $viewRenderer,
        $config
    ) {
        parent::__construct($entityMgr);
        $this->loggedInUser=$userMgr->getLoggedInUser();
        $this->loggedInAdminPermissions=$userMgr->getLoggedInAdminPermissions();
        $this->pluginManager=$pluginManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * @return bool
     */
    function adminIsLoggedIn(){
        return is_a(
            $this->loggedInAdminPermissions,'\Rcm\Entity\AdminPermissions'
        );
    }

    /**
     * This function put the environment together.
     *
     * @return void
     */
    public function init()
    {

        //Create Initial View Object
        $this->view = new ViewModel();

        //WE SHOULD INJECT THIS INSTEAD OF USING SERVICE LOCATOR FROM INSIDE
        //CONTROLLER TO MAKE TESTING AND DEPENDENCY-VIEWING EASIER
        /** @var \Rcm\Entity\Site siteInfo  */
        $this->siteInfo = $this->getServiceLocator()->get('rcmSite');

        //Check Domain and redirect if needed
        $domain = $this->siteInfo->getDomain();
        if (!$this->isRequestDomainPrimary($domain)) {
            return $this->redirectToPrimary($domain);
        }

        //Check for restricted site
        if($this->siteInfo->isLoginRequired()
            && (
                empty($this->loggedInUser)
                || (!$this->siteInfo->isPermitted($this->loggedInUser->getAcountType()) && !$this->adminIsLoggedIn())
            )
        ) {
            $this->redirectToLoginPage();
        }
    }

    /**
     * Is the domain the primary domain?
     *
     * @param \Rcm\Entity\Domain $domain Domain Name Entity
     *
     * @return bool
     */
    public function isRequestDomainPrimary(\Rcm\Entity\Domain $domain)
    {
        $requestedDomain = $_SERVER['HTTP_HOST'];
        $primaryDomain = $domain->getDomainName();

        if ($requestedDomain == $primaryDomain) {
            return true;
        }

        return false;
    }

    /**
     * Will redirect the user to the Primary domain for the Domain name passed
     * in.
     *
     * @param \Rcm\Entity\Domain $domain Domain Name Entity
     *
     * @return mixed
     */
    public function redirectToPrimary(\Rcm\Entity\Domain $domain)
    {
        if (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443
        ) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $requestedUri = $_SERVER['REQUEST_URI'];
        $domainName = $domain->getDomainName();

        $redirectUrl = $protocol . $domainName . $requestedUri;

        return $this->redirect()->toUrl($redirectUrl)->setStatusCode(301);
    }

    public function redirectToLoginPage()
    {
        return $this->redirect()->toUrl($this->siteInfo->getLoginPage())->setStatusCode(301);
    }

    function ensureAdminIsLoggedIn()
    {
        if (!$this->adminIsLoggedIn()
        ) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'You must be logged in to use the requested controller'
            );
        }
    }

    protected function adminSaveInit($pageType='N')
    {

        $this->ensureAdminIsLoggedIn();

        $pageName = $this->getEvent()->getRouteMatch()->getParam('page');
        $pageRevisionId = $this->getEvent()->getRouteMatch()->getParam(
            'revision'
        );

        /** @var \Rcm\Entity\Page $page  */
        $this->page = $this->siteInfo->getPageByName($pageName, $pageType);

        if (empty($this->page)) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'Page Not Found'
            );
        }

        /** @var \Rcm\Entity\PageRevision $pageRevision  */
        $this->pageRevision = $this->page->getRevisionById($pageRevisionId);


        if (empty($this->pageRevision)) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'Page Revision Not Found'
            );
        }
    }

    /**
     * Gets all the views available for the site/domain for use with the
     * create new page option in the admin section.
     *
     * @return mixed
     */
    protected function getPageLayoutsForNewPages()
    {
        $config = $this->config;
        $theme = $this->siteInfo->getTheme();

        if (!empty($config['Rcm']['themes'][$theme]['layouts'])) {
            return $config['Rcm']['themes'][$theme]['layouts'];
        } else {
            return $config['Rcm']['themes']['generic']['layouts']['default'];
        }
    }

    protected function getPageUrl($pageName) {
        $urlParams['page']= $pageName;

        //Check for default language
        if ($this->siteInfo->getDomain()->getDefaultLanguage()->getLanguage() !=  $this->siteInfo->getLanguage()->getLanguage()) {
            $urlParams['language'] = $this->siteInfo->getLanguage()->getLanguage();
        }

        return $this->url()->fromRoute(
            'contentManager',
            $urlParams
        );
    }
}