<?php

namespace Rcm\Controller;

use \RcmSimpleConfigStorage\Entity\InstanceConfig as JsonContent,
    \RcmSimpleConfigStorage\StorageEngine\DoctrineSerializedRepo;


class InstallController extends \Rcm\Controller\EntityMgrAwareController
{
    protected $instances = array();
    protected $siteWideInstances = array();
    /**
     * @var \Rcm\Entity\Site
     */
    protected $site=null;

    protected $instanceRepo;

    protected $pluginManager;

    protected $countryRepo;
    protected $languageRepo;

    function __construct(
        \Doctrine\ORM\EntityManager $entityMgr,
        \Rcm\Model\PluginManager $pluginManager
    ) {
        parent::__construct($entityMgr);
        $this->pluginManager=$pluginManager;
        $this->instanceRepo = new DoctrineSerializedRepo($entityMgr);
        $this->countryRepo = $this->entityMgr->getRepository('\Rcm\Entity\Country');
        $this->languageRepo = $this->entityMgr->getRepository('\Rcm\Entity\Language');
    }

    public function indexAction()
    {

        ini_set('max_execution_time', 0);

        $this->checkEnvironmentRequirements();

        $this->fixSymLinks();

        $this->initializeDatabase(true);

        $countryRepo = $this->entityMgr->getRepository('\Rcm\Entity\Country');
        $languageRepo = $this->entityMgr->getRepository('\Rcm\Entity\Language');

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
        $this->entityMgr->flush();


        $view = new \Zend\View\Model\ViewModel(
            array('content'=>'Install Complete')
        );
        $view->setTemplate('rcm/literal');
        return $view;
    }

    function createBasicPage($urlName ,$title, $metaDesc, $keywords, $html, $site){
        $this->instances = array();
        $this->instanceRepo->createInstanceConfig(
            $this->createInstance('RcmHtmlArea', 4, 0),
            array('html' => $html)
        );

        $this->pageFactory->createPage(
            $urlName,
            'Migration Script',
            $title,
            $metaDesc,
            $keywords,
            'GuestSitePage',
            $site,
            array_merge($this->instances, $this->siteWideInstances)
        );
    }

    /**
     * @TODO check for unset timezones here to so we can remove that from docs
     * @throws \Exception
     */
    function checkEnvironmentRequirements(){
        if(get_magic_quotes_gpc()){
            throw new \Exception('Magic quotes must be OFF for Rcm');
        }
    }

    function fixSymLinks(){
        require(
            __DIR__
                .'/../../../scripts/makeSymlinksForZf2ModulePublicFolders.php'
        );
    }

    function buildSite($countryName, $languageName, $domain=null){
        //Create US En Site
        /** @var \Rcm\Entity\Country $country */
        $country = $this->countryRepo->find($countryName);

        /** @var \Rcm\Entity\Language $language */
        $language = $this->languageRepo->findOneBy(array('iso639_2b' => $languageName));

        $this->entityMgr->flush();

        return $this->createSite($country, $language, $domain);
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
        $userManager->newUser($email,$password,1);
        $adminPermissions= new \Rcm\Entity\AdminPermissions();
        $adminPermissions->setAccountNumber(1);
        $this->entityMgr->persist($adminPermissions);
    }

    function getDefaultHtmlAreaContent(){
        return $this->getNewInstanceConfig(
            'vendor/reliv/RcmPlugins/RcmHtmlArea/'
        );
    }

    function getNewInstanceConfig($pluginPath){
        return include $pluginPath.'/config/newInstanceConfig.php';
    }

    function createHomePage(){
        $this->instances = array();

        $this->createJsonInstance(
            'RcmHtmlArea',
            array(
                'html' => '
<table syle="width: 100%;" class="homePageTable">
    <tr>
        <td class="homePageTableTd">
            <p>
	            <strong>Complete &quot;What You See Is What You Get&quot; website editor</strong>
	        </p>
            <p>
	            R-Witer has been built from the ground up to be a WYSIWYG website editor. &nbsp; Simply edit the site
	            as is and publish. &nbsp;No need to open another browser, or go back and forth between preview and edit
	            screens to see how your changes will look.
	        </p>
            <p>&nbsp;</p>
            <p>
	            <strong>Built for teams of all sizes</strong>
	        </p>
            <p>
	            R-Writer is built to be used in big teams with multiple departments, or for your personal use. &nbsp;
	            Create multiple admin accounts, each with independent access rights. &nbsp;Grant rights to indidual
	            sites, pages, or even on a per plugin bases. &nbsp;This allows for complex settings where you could
	            grant full access to one person and then grant access to one component, page, or site to another.
	        </p>
            <p>&nbsp;</p>
            <p>
	            <strong>Developer freindly</strong></p>
            <p>
	            R-Writer is built on top Zend Framework 2. &nbsp; Giving developers a comfortable standardized way to
	            develop and extend the system to their needs. &nbsp;R-Writer itself is nothing more then a plugin
	            module for Zend Framework 2. &nbsp;
	        </p>

        </td>
        <td style="width: 378px; vertical-align: text-top;" >
            <img src="/modules/rcm-generic/images/house.jpg">
        </td>
    </tr>
</table>'
            ),
            2,
            0
        );

        $this->createJsonInstance(
            'RcmPortalAnnouncementBox',
            array(
                'top' => '',
                'text' => 'Click to read more',
                'href' => 'http://rwriter.org/forums',
                'html' => '
<img alt="Yummy Pastries" src="/modules/rcm-generic/images/pastries.jpg" />
<p>
    If you&lsquo;re having problems editing this website template, then don&lsquo;t hesitate to ask for help on
    the <a href="http://rwriter.org/forums">Forums</a>.
</p>
'
            ),
            2,
            1
        );

        $this->createJsonInstance(
            'RcmPortalAnnouncementBox',
            array(
                'top' => '',
                'text' => 'Click to read more',
                'href' => '/login',
                'html' => '
<img alt="Yummy Pastries" src="/modules/rcm-generic/images/fruits.jpg" />
<p>
    To begin editing your new site simply <a href="http://rwriter.org/forums">Login</a>.
</p>
'
            ),
            2,
            2
        );

        $this->createJsonInstance(
            'RcmPortalAnnouncementBox',
            array(
                'top' => '',
                'text' => 'Click to read more',
                'href' => 'http://www.freewebsitetemplates.com/',
                'html' => '
<img alt="Yummy Pastries" src="/modules/rcm-generic/images/cosmetics.jpg" />
<p>
    This website template has been designed by <a href="http://www.freewebsitetemplates.com/">Free Website Templates</a>.
    for you, for free. You can replace all this text with your own text.
</p>
'
            ),
            2,
            3
        );


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

        $content = array(
            "loginHeader" => "Login",
            "userNameCopy" => "Username:",
            "loginPasswordCopy" => "Password",
            "loginSubmitCopy" => "Login",
            "loginForgotPasswordCopy" => "",
            "loginErrorInvalidCopy" => "Incorrect Username or Password",
            "bottomLoginText" => ""
        );

        $this->createJsonInstance('RcmLogin', $content, 2, 0);

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

        $content = array( 'html' => '
<h1>R-Writer License (New BSD)</h1>
<p>Copyright<sup>&copy;</sup> 2012, Reliv\' International, Inc.</p>
<p>All rights reserved.</p>
<p>&nbsp;</p>
<p>Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:</p>
<ul>
    <li>Redistributions of source code must retain the above copyright notice,
        this list of conditions and the following disclaimer.
    </li>

    <li>Redistributions in binary form must reproduce the above copyright notice,
        this list of conditions and the following disclaimer in the documentation
        and/or other materials provided with the distribution.
    </li>

    <li>Neither the name of Reliv\' International, Inc. nor the names of its
      contributors may be used to endorse or promote products derived from this
      software without specific prior written permission.
    </li>
</ul>
<p>&nbsp;</p>

<p>
    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
    ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
    ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
</p>

<p>&nbsp;</p>
<h1>Other Licences</h1>
<p>R-Writer is dependent on other open source packages. These are independent of R-Writer and each has it\'s own
        license.  These are listed below:</p>
<p>&nbsp;</p>
<ul>
    <li><a href="http://framework.zend.com/">Zend Framework 2</a> - License: <a href="http://framework.zend.com/license">New BSD</a></li>
    <li><a href="http://www.doctrine-project.org/">Doctrine 2</a> - License: <a href="https://github.com/doctrine/doctrine2/blob/master/LICENSE">MIT</a></li>
    <li><a href="http://jquery.com/">JQuery</a> - License: <a href="http://jquery.org/license/">MIT</a></li>
    <li><a href="http://www.malsup.com/jquery/block/">JQuery BlockUI</a> - Duel License:
        <a href="http://www.opensource.org/licenses/mit-license.php">MIT</a> /
        <a href="http://www.gnu.org/licenses/gpl.html">GPL</a>
    </li>

    <li>Editors (Optional)
        <ul>
            <li><a href="http://ckeditor.com/">CkEditor</a> -
                Triple License:
                <a href="http://www.gnu.org/licenses/gpl.html">GPL</a> /
                <a href="http://www.gnu.org/licenses/lgpl.html">LGPL</a> /
                <a href="http://www.mozilla.org/MPL/">MPL</a>
            </li>

            <li><a href="http://www.tinymce.com/">TinyMCE</a> - License: <a href="http://www.tinymce.com/wiki.php/License">GPL</a></li>
            <li><a href="http://aloha-editor.org/">Aloha Editor</a> - License: <a href="http://aloha-editor.org/license.php">GPL</a>
                (Commercial licenses may be purchased)
            </li>
        </ul>
    </li>
</ul>
        ');

        $this->createJsonInstance(
            'RcmHtmlArea', $content, 2, 0
        );

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

        $this->createJsonInstance(
            'RcmHtmlArea', $this->getDefaultHtmlAreaContent(), 2, 0
        );

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

        $this->createJsonInstance(
            'RcmHtmlArea',
            array(
                'html' => '<h1>R-Writer</h1>',
            ),
            1,
            0,
            true,
            'SiteName'
        );

        $homeLink = $this->url()->fromRoute(
            'contentManager',
            array(
                'page' => 'index'
            )
        );

        $loginLink = $this->url()->fromRoute(
            'contentManager',
            array(
                'page' => 'login'
            )
        );

        $licenseLink = $this->url()->fromRoute(
            'contentManager',
            array(
                'page' => 'license'
            )
        );

        $contactLink = $this->url()->fromRoute(
            'contentManager',
            array(
                'page' => 'contact'
            )
        );

        $content= array(
            'html'=>'
                <li><a href="'.$homeLink.'">Home</a></li>
                <li><a href="'.$loginLink.'">Login</a></li>
                <li><a href="'.$licenseLink.'">License</a></li>
                <li><a href="'.$contactLink.'">Contact</a></li>
            '
        );
        $this->createJsonInstance(
            'RcmNavigation', $content, 1, 1, true, 'Site Navigation'
        );

        $this->createJsonInstance(
            'RcmNavigation', $content, 3, 0, true, 'Footer Navigation'
        );

        $content= array(
            'html'=>'<p>R-Writer is free software and licensed under the New BSD License</p>'
        );


        $this->createJsonInstance(
            'RcmHtmlArea', $content, 3, 1, true, 'Footer Copyright Notice'
        );

//        $this->createJsonInstance(
//            'RcmRssFeed', null, 5, 0, true, 'Blog Feed'
//        );
    }

    public function createSite(
        \Rcm\Entity\Country $country,
        \Rcm\Entity\Language $language,
        $domain
    ) {

        $this->siteWideInstances = array();

        $this->createSiteWideContent();

        $this->site = $this->getSiteFactory()->createNewSite(
            $domain,
            'RcmGeneric',
            $country,
            $language,
            7,
            '',
            $this->siteWideInstances
        );

        $this->createHomePage();
        $this->createLoginPage();
        $this->createLicensePage();
        $this->createContactPage();

        return $this->site;
    }

    /*
    * Runs all sql files in the given folder.
    * SQL Queries must end with ;\n for this to work
    *
    * @return null
    */
    function runAllSqlFilesInDir($directory)
    {
        $conn = $this->entityMgr->getConnection();
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
     * \RcmSimpleConfigStorage\JsonContentController
     *
     * @param string    $pluginName
     * @param array  $jsonContent
     * @param int    $container
     * @param int    $renderOrder
     * @param bool   $siteWide
     * @param string $siteWidePluginName
     *
     * @return null
     */
    function createJsonInstance(
        $pluginName,
        $jsonContent,
        $container,
        $renderOrder = 0,
        $siteWide = false,
        $siteWidePluginName = '',
        $forceWidth = null
    ){
            $this->instanceRepo->createInstanceConfig(
                $this->createInstance(
                    $pluginName,
                    $container,
                    $renderOrder,
                    $siteWide,
                    $siteWidePluginName,
                    $forceWidth
                ),
                $jsonContent
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
        $pluginName,
        $container,
        $renderOrder = 0,
        $siteWide = false,
        $siteWidePluginName = '',
        $forceWidth = null
    )
    {
        $pageInstance = new \Rcm\Entity\PagePluginInstance();

        $pageInstance->setLayoutContainer($container);
        $pageInstance->setRenderOrderNumber($renderOrder);
        if(is_numeric($forceWidth)){
            $pageInstance->setWidth($forceWidth);
        }

        $instance = new \Rcm\Entity\PluginInstance();
        $instance->setPlugin($pluginName);
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

        $this->entityMgr->persist($pageInstance);
        $this->entityMgr->persist($instance);
        $this->entityMgr->flush();
        return $instance->getInstanceId();
    }

    /**
     * Tells Doctrine to empty the database
     *
     * @return null
     */
    function dropDatabase(){

        try{

            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityMgr);
            $schemaTool->dropDatabase();

        }catch(\Exception $e){

            //This fixes errors with removing tables wth foreign keys in MYSQL
            $conn = $this->entityMgr->getConnection();
            $params = $conn->getParams();
            $databaseName = $params['master']['dbname'];
            $conn->exec('drop database ' . $databaseName);
            $conn->exec('create database ' . $databaseName);
            $conn->exec('use ' . $databaseName);
        }

    }

    /**
     * Tells Doctrine to create all the tables we need in the database based on
     * our php entity class files
     *
     * @return null
     */
    function buildDatabase(){
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityMgr);
        $schemaTool->createSchema(
            $this->entityMgr->getMetadataFactory()->getAllMetadata()
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
        $entityMgr = $this->entityMgr;
        $postCodeRepo = $entityMgr->getRepository(
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

        /** @var \Rcm\Entity\Address $billAddress  */
        $billAddress = $newAdminUser->getBillAddress();
        $billAddress->setAddressLine1('55 Spring Branch Rd');
        $billAddress->setCity('Troy');
        $billAddress->setState('MO');
        $billAddress->setCountry($country);

        /** @var \Rcm\Entity\PostalCode $postalCodeEntity  */
        $postalCodeEntity = $postCodeRepo->findOneBy(
            array(
                'postalCode' => '63379'
            )
        );
        $billAddress->setZip($postalCodeEntity);

        /** @var \Rcm\Entity\Address $shipAddress  */
        $shipAddress = $newAdminUser->getShipAddress();
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