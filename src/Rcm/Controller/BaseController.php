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

use Rcm\Model\SiteFactory;

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
class BaseController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Rcm\Entity\Site
     */
    protected $siteInfo;
    protected $config;

    /**
     * @var \RcmLogin\Entity\User
     */
    protected $loggedInUser;

    /** @var \Rcm\Entity\Page $page */
    protected $page;

    /**
     * @var \Zend\View\Model\ViewModel Zend View Model
     */
    protected $view;

    /**
     * This function put the environment together.
     *
     * @return void
     */
    public function init()
    {
        $this->setConfig();

        //Create Initial View Object
        $this->view = new ViewModel();

        $this->setSiteInfo();

        //Check Domain and redirect if needed
        $domain = $this->siteInfo->getDomain();
        if (!$this->isRequestDomainPrimary($domain)) {
            return $this->redirectToPrimary($domain);
        }

        /** @var \RcmLogin\Model\UserManagement\UserManagementInterface $userManager  */
        $userManager = $this->getServiceLocator()->get('rcmUserManager');

        if (!empty($userManager)) {
            $this->loggedInUser = $userManager->getLoggedInUser();
        }

    }

    /**
     * Get ZF2 config
     *
     * @return array
     */
    public function getConfig()
    {
        if (empty($this->config)) {
            return $this->setConfig();
        }

        return $this->config;
    }

    /**
     * Set the config for the controller.  If none is passed it will attempt
     * to retrieve it from the service manager.
     *
     * @param array $config Array of Configurations
     *
     * @return array
     */
    public function setConfig($config = null)
    {
        if (!empty($config) && is_array($config)) {
            $this->config = $config;
        } else {
            $this->config = $this->getServiceLocator()->get('config');
        }

        return $this->config;
    }

    /**
     * Gets the doctrine entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        $emClass = 'Doctrine\ORM\EntityManager';

        //If the entity manger was not injected, go get it.
        if (!is_a($this->entityManager, $emClass)) {
            $this->entityManager = $this->getServiceLocator()->get($emClass);
        }

        return $this->entityManager;
    }

    /**
     * Sets the doctrine entity manager - this is used for testing only
     *
     * @param $entityManager \Doctrine\ORM\EntityManager doctrine entity manager
     *
     * @return null
     */
    function setEm($entityManager)
    {
        $this->entityManager = $entityManager;
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

    /**
     * Set the site info while falling back to the default domain and if
     * necessary. This calls getSite() which will fall back to a domain's
     * default language if necessary.
     *
     * @return null
     */
    public function setSiteInfo()
    {
        $appConfig = $this->getServiceLocator()->get('config');
        $siteFactory = $this->getServiceLocator()->get(
            'Rcm\Model\SiteFactory'
        );

        $language = $this->getEvent()->getRouteMatch()->getParam('language');


        try {
            $this->siteInfo = $siteFactory->getSite(
                $_SERVER['HTTP_HOST'],
                $language
            );
        } catch (\Rcm\Exception\SiteNotFoundException $e) {
            $this->siteInfo = $siteFactory->getSite(
                $appConfig['reliv']['defaultDomain'],
                $language
            );
        }
    }

    /**
     * Prep a plugin instance to be passed to the View layer
     *
     * @param \Rcm\Entity\PluginInstance $instance plugin Instance
     *
     * @return \Rcm\Entity\PluginInstance $instance plugin Instance
     * @throws \Exception
     */
    protected function prepPluginInstance(
        \Rcm\Entity\PluginInstance $instance
    )
    {
        $this->loadPlugin($instance);

        $config = $this->getServiceLocator()->get('config');

        $pluginName = $instance->getName();


        if (isset($config['rcmPlugin'][$pluginName]['editJs'])) {
            $instance->setAdminEditJs(
                $config['rcmPlugin'][$pluginName]['editJs']
            );
        }

        if (isset($config['rcmPlugin'][$pluginName]['editCss'])) {
            $instance->setAdminEditCss(
                $config['rcmPlugin'][$pluginName]['editCss']
            );
        }

        if (isset($config['rcmPlugin'][$pluginName]['display'])
            && !$instance->isSiteWide()
        ) {
            $instance->setDisplayName(
                $config['rcmPlugin'][$pluginName]['display']
            );
        }

        if (isset($config['rcmPlugin'][$pluginName]['tooltip'])) {
            $instance->setTooltip($config['rcmPlugin'][$pluginName]['tooltip']);
        }

        if (isset($config['rcmPlugin'][$pluginName]['icon'])) {
            $instance->setIcon($config['rcmPlugin'][$pluginName]['icon']);
        }

        return $instance;
    }

    public function loadPlugin(
        \Rcm\Entity\PluginInstance $instance
    )
    {
        if ($instance->getInstanceId() < 0) {
            $view = $this->callPlugin(
                $instance,
                'renderDefaultInstance'
            );
        } else {
            $view = $this->callPlugin(
                $instance,
                'renderInstance'
            );
        }

        $instance->setViewModel($view);
    }

    public function savePlugin(
        \Rcm\Entity\PluginInstance $instance,
        $dataToSave
    )
    {
        $this->callPlugin(
            $instance,
            'saveInstance',
            $dataToSave
        );
    }


    public function callPlugin(
        \Rcm\Entity\PluginInstance $instance,
        $action,
        $dataToPass = array()
    )
    {

        $pluginName = $instance->getName();

        if (!$this->moduleIsLoaded($pluginName)) {
            throw new \Exception(
                "Plugin $pluginName is not loaded or configured. Check
                config/application.config.php"
            );
        }

        $pluginController = $this->getPluginController($pluginName);

        if (empty($dataToPass)) {
            return $pluginController->{$action}($instance->getInstanceId());
        }

        return $pluginController->{$action}(
            $instance->getInstanceId(), $dataToPass
        );
    }

    /**
     * Gets cached plugin controller. Creates one if not in cache
     * @param $pluginName
     *
     * @return mixed
     * @throws \Exception
     */
    function getPluginController($pluginName)
    {
        //Load the plugin controller
        $pluginController = $this->serviceLocator->get($pluginName);

        //Plugin controllers must implement this interface
        if (!$pluginController instanceof \Rcm\Controller\PluginInterface) {
            throw new \Exception(
                'Class "' . get_class($pluginController) . '" for plugin "'
                    . $pluginName . '" does not implement '
                    . '\Rcm\Controller\PluginInterface'
            );
        }

        //If the plugin controller can accept a ZF2 event, pass it
        if (method_exists($pluginController, 'setEvent')) {
            $pluginController->setEvent($this->getEvent());
        }

        return $pluginController;
    }

    function moduleIsLoaded($moduleName)
    {
        $moduleManager = $this->getServiceLocator()->get('modulemanager');

        $loadedModules = $moduleManager->getLoadedModules();

        return isset($loadedModules[$moduleName]);
    }

    /**
     * @TODO FIX THIS
     */
    function adminIsLoggedIn()
    {
        return true;
        /*return $this->loggedInUser
            && is_a($this->loggedInUser->getAdminInfo(),'\RcmLogin\Entity\AdminUser')
            && $this->loggedInUser->getAdminInfo()->isAdmin();*/
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

    protected function adminSaveInit()
    {

        $this->ensureAdminIsLoggedIn();

        $this->setConfig();
        $pageName = $this->getEvent()->getRouteMatch()->getParam('page');
        $pageRevisionId = $this->getEvent()->getRouteMatch()->getParam(
            'revision'
        );

        /** @var \Rcm\Entity\Page $page  */
        $this->page = $this->siteInfo->getPageByName($pageName);

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
}