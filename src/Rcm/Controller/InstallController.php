<?php

namespace Rcm\Controller;

use \RcmPluginCommon\Entity\JsonContent as JsonContent;


class InstallController extends \Rcm\Controller\BaseController
{
    protected $instances = array();
    protected $siteWideInstances = array();
    /**
     * @var \Rcm\Entity\Site
     */
    private $site=null;

    public function indexAction()
    {

        ini_set('max_execution_time', 0);

        $this->fixSymLinks();

        $this->initializeDatabase(true);

        $countryRepo = $this->getEm()->getRepository('\Rcm\Entity\Country');
        $languageRepo = $this->getEm()->getRepository('\Rcm\Entity\Language');

        /** @var \Rcm\Entity\Country $country  */
        $country = $countryRepo->find('USA');

        /** @var \Rcm\Entity\Language $language  */
        $language = $languageRepo->findOneBy(array('iso639_2b' => 'eng'));

        $this->createSite($country, $language);



        $this->createUser('admin@admin.com','admin');

        /*$this->createBaseAdminUser(
            $country,
            $this->site,
            1,
            'John',
            'Smith',
            'johnsmith@johnsmith.johnsmith'
        );*/
        $this->getEm()->flush();


        $view = new ViewModel(array('content'=>'Install Complete'));
        $view->setTemplate('rcm/literal');
        return $view;
    }

    function fixSymLinks(){
        require(
            __DIR__
                .'/../../../scripts/makeSymlinksForZf2ModulePublicFolders.php'
        );
    }

    function initializeDatabase($dropDatabaseFirst = false){

        if($dropDatabaseFirst){
            $this->dropDatabase();
        }

        $this->buildDatabase();

        $this->runAllSqlFilesInDir('/../../../../Rcm/install-data/mysql');
    }

    /**
     * this is needed to override the parent's init which does things we don't want to do here
     */
    function init(){
    }

    function createUser($email,$password){
        $userManager=$this->serviceLocator->get('rcmUserManager');
        $userManager->newPerson($email,$password);
    }

    function createHomePage(){
        $this->instances = array();

        $this->createJsonInstance('RcmHtmlArea', null, 6, 0);

        $this->getPageFactory()->createPage(
            'index',
            'John Smith',
            'Home Page',
            'Page Description',
            'page,key,words',
            'twoColumn',
            $this->site,
            $this->getCurrentInstances()
        );
    }

    function createLoginPage(){
        $this->instances = array();

        $this->createJsonInstance('RcmLogin', null, 6, 0);

        $this->getPageFactory()->createPage(
            'login',
            'John Smith',
            'Home Page',
            'Page Description',
            'page,key,words',
            'twoColumn',
            $this->site,
            $this->getCurrentInstances()
        );
    }

    function createLicensePage(){
        $this->instances = array();

        $this->createJsonInstance('RcmHtmlArea', null, 6, 0);

        $this->getPageFactory()->createPage(
            'license',
            'John Smith',
            'License Page',
            'Page Description',
            'page,key,words',
            'twoColumn',
            $this->site,
            $this->getCurrentInstances()
        );
    }

    function createContactPage(){
        $this->instances = array();

        $this->createJsonInstance('RcmHtmlArea', null, 6, 0);

        $this->getPageFactory()->createPage(
            'contact',
            'John Smith',
            'License Page',
            'Page Description',
            'page,key,words',
            'twoColumn',
            $this->site,
            $this->getCurrentInstances()
        );
    }
    
    function getCurrentInstances(){
        return array_merge($this->instances, $this->siteWideInstances);
    }

    function createSiteWideContent(){
        $content= array(
            'html'=>'
                <li><a href="/rcm/">Home</a></li>
                <li><a href="/rcm/login">Login</a></li>
                <li><a href="/rcm/license">License</a></li>
                <li><a href="/rcm/contact">Contact</a></li>
            '
        );
        $this->createJsonInstance(
            'RcmNavigation', $content, 2, 0, true, 'Site Navigation'
        );

        $content= array(
            'html'=>'RCM is free software and licensed under the New BSD License'
        );
        $this->createJsonInstance(
            'RcmHtmlArea', $content, 4, 0, true, 'Footer Details'
        );

        $this->createJsonInstance(
            'RcmRssFeed', null, 5, 0, true, 'Blog Feed'
        );
    }

    public function createSite(
        \Rcm\Entity\Country $country,
        \Rcm\Entity\Language $language,
        $subDomain=''
    ) {
        if (!empty($subDomain)) {
            $subDomain = $subDomain.'.'.$_SERVER['HTTP_HOST'];
        } else {
            $subDomain = $_SERVER['HTTP_HOST'];
        }

        $this->siteWideInstances = array();

        $this->createSiteWideContent();

        $this->site = $this->getSiteFactory()->createNewSite(
            $subDomain,
            $country,
            $language,
            7,
            'local.reliv.com',
            $this->siteWideInstances
        );

        $this->createHomePage();
        $this->createLoginPage();
        $this->createLicensePage();
        $this->createContactPage();
    }

    /**
     * Set the Doctorine or DB EntityManager.  Use Service Manager to inject
     * instance.
     */
    public function getEm()
    {
        if (null === $this->entityManager) {
            $this->entityManager
                = $this->getServiceLocator()->get(
                'doctrine.entitymanager.orm_default'
            );
        }
        return $this->entityManager;
    }

    /*
    * Runs all sql files in the given folder.
    * SQL Queries must end with ;\n for this to work
    *
    * @return null
    */
    function runAllSqlFilesInDir($directory)
    {
        $conn = $this->getEm()->getConnection();
        $path = __DIR__;
        $path .= $directory;
        $dir = openDir($path);
        while ($fileName = readdir($dir)) {
            $fileContents = file_get_contents($path . '/' . $fileName);
            $queries = explode(";\n", $fileContents);
            foreach ($queries as $sql) {
                if (!empty($sql)) {
                    $conn->exec($sql);
                }
            }
        }
    }

    /**
     * Creates a plugin instance for plugins that have controllers that extend
     * \RcmPluginCommon\JsonContentController
     *
     * @param int    $pluginName
     * @param array  $jsonContent
     * @param int    $container
     * @param int    $renderOrder
     * @param bool   $siteWide
     * @param string $siteWidePluginName
     *
     * @return null
     */
    function createJsonInstance(
        $pluginName, $jsonContent=null, $container, $renderOrder = 0, $siteWide = false, $siteWidePluginName = ''
    ){
        if(empty($jsonContent)){
            $controllerClass = '\\'.$pluginName.'\\Controller\\PluginController';
            $pluginController=new $controllerClass();
            $jsonContent=$pluginController->getDefaultJsonContent();
        }
        $this->getEm()->persist(
            New JsonContent(
                $this->createInstance(
                    $pluginName,
                    $container,
                    $renderOrder,
                    $siteWide,
                    $siteWidePluginName
                ),
                $jsonContent
            )
        );
    }

    /**
     * Creates a plugin instance
     *
     * @param string $pluginName
     * @param int    $container
     * @param int    $renderOrder
     * @param bool   $siteWide
     * @param string $siteWidePluginName
     *
     * @return int
     */
    function createInstance(
        $pluginName, $container, $renderOrder = 0, $siteWide = false, $siteWidePluginName = ''
    )
    {
        $pageInstance = new \Rcm\Entity\PagePluginInstance();
        $instance = new \Rcm\Entity\PluginInstance();
        $instance->setPlugin($pluginName);

        $pageInstance->setLayoutContainer($container);
        $pageInstance->setRenderOrderNumber($renderOrder);
        $pageInstance->setInstance($instance);

        if ($siteWide !== false) {
            $instance->setSiteWide();
            $this->siteWideInstances[] = $pageInstance;
        } else {
            $this->instances[] = $pageInstance;
        }

        if (!empty($siteWidePluginName)) {
            $instance->setDisplayName($siteWidePluginName);
        }

        $this->getEm()->persist($pageInstance);
        $this->getEm()->persist($instance);
        $this->getEm()->flush();
        return $instance->getInstanceId();
    }

    /**
     * Tells Doctrine to empty the database
     *
     * @return null
     */
    function dropDatabase(){
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->getEm());
        $schemaTool->dropDatabase();
    }

    /**
     * Tells Doctrine to create all the tables we need in the database based on
     * our php entity class files
     *
     * @return null
     */
    function buildDatabase(){
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->getEm());
        $schemaTool->createSchema(
            $this->getEm()->getMetadataFactory()->getAllMetadata()
        );

    }

    /**
     * Shortcut to get SiteFactory from service locator
     *
     * @return \Rcm\Model\SiteFactory
     */
    function getSiteFactory(){
        return $this->getServiceLocator()->get('Rcm\Model\SiteFactory');
    }

    /**
     * Shortcut to get PageFactory from service locator
     *
     * @return \Rcm\Model\PageFactory
     */
    function getPageFactory(){
        return $this->getServiceLocator()->get('Rcm\Model\PageFactory');
    }

    public function createBaseAdminUser(
        $country,
        $siteInfo,
        $rcn,
        $firstName,
        $lastName,
        $email
    ) {
        $entityManager = $this->getEm();
        $postCodeRepo = $entityManager->getRepository(
            '\Rcm\Entity\PostalCode'
        );

        //Create New Admin User
        /** @var \RcmLogin\Model\UserManagement\DoctrineUserManager $userManager  */
        $userManager = $this->getServiceLocator()->get(
            'RcmLogin\Model\UserManagement\DoctrineUserManager'
        );

        /** @var \RcmLogin\Entity\User $newAdminUser  */
        $newAdminUser = $userManager->getNewUserInstance();

        $newAdminUser->setAccountNumber($rcn);
        $newAdminUser->setPassword('Reliv1');
        $newAdminUser->setAccountStatus('E');
        $newAdminUser->setAccountRank('1');
        $newAdminUser->setDateOfBirth(new \DateTime("1977-12-09"));
        $newAdminUser->setEmail($email);
        $newAdminUser->setFirstName($firstName);
        $newAdminUser->setLastName($lastName);
        $newAdminUser->setSsn('555555555');

        /** @var \Rcm\Entity\Address $billingAddress  */
        $billingAddress = $newAdminUser->getBillingAddress();
        $billingAddress->setAddressLine1('55 Spring Branch Rd');
        $billingAddress->setCity('Troy');
        $billingAddress->setState('MO');
        $billingAddress->setCountry($country);

        /** @var \Rcm\Entity\PostalCode $postalCodeEntity  */
        $postalCodeEntity = $postCodeRepo->findOneBy(
            array(
                'postalCode' => '63379'
            )
        );
        $billingAddress->setZip($postalCodeEntity);

        /** @var \Rcm\Entity\Address $shipAddress  */
        $shipAddress = $newAdminUser->getShippingAddress();
        $shipAddress->setAddressLine1('55 Spring Branch Rd');
        $shipAddress->setCity('Troy');
        $shipAddress->setState('MO');
        $shipAddress->setCountry($country);
        $shipAddress->setZip($postalCodeEntity);

        $phoneNumbers = $newAdminUser->getPhoneNumbers();

        /** @var \Rcm\Entity\PhoneNumber $homePhone  */
        $homePhone = $phoneNumbers['h'];
        $mobilePhone = clone $homePhone;

        $homePhone->setAreaCode(636);
        $homePhone->setCountryCode(1);
        $homePhone->setNumber(6362338078);
        $homePhone->setType('h');

        $mobilePhone->setAreaCode(636);
        $mobilePhone->setCountryCode(1);
        $mobilePhone->setNumber(6362338078);
        $mobilePhone->setType('m');


        $newAdminUser->setPhoneNumber($mobilePhone);


        /** @var \RcmLogin\Entity\AdminUser $adminInfo  */
        $adminInfo = $newAdminUser->getAdminInfo();

        foreach ($siteInfo as $oneSite) {
            $adminInfo->setAllowedSite($oneSite);
        }

        $adminInfo->setCreateFromTemplate(true);
        $adminInfo->setCreateNewPage(true);
        $adminInfo->setEditSiteWidePlugins(true);
        $adminInfo->setManagePageLayout(true);
        $adminInfo->setAccountNumber($rcn);

        $userManager->saveUser($newAdminUser);
    }

}