<?php
/**
 * Index Controller for the entire application
 *
 * This file contains the main controller used for the application.  This
 * should extend from the base class and should need no further modification.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Unkown <unknown@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace Rcm\Controller;

use \Rcm\Controller\BaseController,
    \Rcm\Entity\PageRevision,
    \Rcm\Entity\PluginInstance,
    \Rcm\Entity\PluginAsset;
use Rcm\Entity\Domain;
use RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig;

/**
 * Index Controller for the entire application
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class AdminController extends BaseController
{


    public function newPageWizardAction()
    {
        $this->ensureAdminIsLoggedIn();


        $viewVars['rcmTemplates'] = $this->siteInfo->getTemplates();

        $viewVars['newPageLayoutContainers'] = $this->getPageLayoutsForNewPages();

        return $viewVars;

    }

    public function newSiteWizardAction()
    {
        $this->ensureAdminIsLoggedIn();

        $viewVars['rcmCurrentSites'] = $this->entityMgr->createQuery(
            '
                        SELECT s.siteId, d.domain
                        FROM \Rcm\Entity\Site s
                        JOIN s.domain d
                        WHERE d.primaryDomain IS NULL
                    '
        )->getArrayResult();

        $viewVars['rcmCountries'] = $this->entityMgr->createQuery(
            '
                        SELECT c.iso3, c.countryName
                        FROM \Rcm\Entity\Country c
                    '
        )->getArrayResult();


        $viewVars['rcmLanguages'] = $this->entityMgr->createQuery(
            '
                        SELECT l.languageId, l.languageName
                        FROM \Rcm\Entity\Language l
                    '
        )->getArrayResult();

        return $viewVars;

    }

    public function createNewUserAction()
    {
        $this->ensureAdminIsLoggedIn();

        /** @var $siteFactory \Rcm\Model\SiteFactory */
        $siteFactory = $this->getServiceLocator()->get(
            'Rcm\Model\SiteFactory'
        );

        $sites = $siteFactory->getAvailableSites();


    }

    public function getSaveAsTemplateAction()
    {
        $this->ensureAdminIsLoggedIn();
    }

    public function checkPageNameJsonAction()
    {
        $this->ensureAdminIsLoggedIn();

        $pageUrl = $this->getRequest()->getQuery()->get('pageUrl');
        $pageType = $this->getRequest()->getQuery()->get('pageType');
        $pageUrl = urlencode($pageUrl);

        if (empty($pageType)) {
            $pageType = 'n';
        }

        $em = $this->entityMgr;
        $page = $this->getPageByName($pageUrl, $pageType);

        if (empty($page)) {
            $data['dataOk'] = 'Y';
        } else {
            $data['dataOk'] = 'N';
        }

        echo json_encode($data);
        exit;
    }

    public function checkUserNameJsonAction()
    {
        $this->ensureAdminIsLoggedIn();

        $userName = $this->getRequest()->getQuery()->get('checkValue');

        /** @var $userManager /Rcm/Model/UserManagement/UserManagerInterface */
        $userManager = $this->getServiceLocator()->get('rcmUserMgr');

        $user = $userManager->isCurrentUser($userName);

        if (empty($user)) {
            $data['dataOk'] = 'Y';
        } else {
            $data['dataOk'] = 'N';
        }

        echo json_encode($data);
        exit;
    }

    public function checkEmailAddressJsonAction()
    {
        $this->ensureAdminIsLoggedIn();

        $email = $this->getRequest()->getQuery()->get('checkValue');

        $validator = new \Zend\Validator\EmailAddress();
        if ($validator->isValid($email)) {
            $data['dataOk'] = 'Y';
        } else {
            $data['dataOk'] = 'N';
        }

        echo json_encode($data);
        exit;
    }

    public function checkDomainJsonAction()
    {
        $this->ensureAdminIsLoggedIn();

        $domain = $this->getRequest()->getQuery()->get('checkValue');

        if ($this->isDomainValid($domain)) {
            $data['dataOk'] = 'Y';
        } else {
            $data['dataOk'] = 'N';
            echo json_encode($data);
            exit;
        }


        echo json_encode($data);
        exit;
    }

    public function getNewInstanceAction()
    {
        $this->ensureAdminIsLoggedIn();

        $routeMatch = $this->getEvent()->getRouteMatch();
        $pluginType = $routeMatch->getParam('type');
        $instanceId = $routeMatch->getParam('instanceId');

        if (empty($instanceId)) {
            $instanceId = -1;
        }

        $instance = new \Rcm\Entity\PluginInstance();
        $instance->setPlugin($pluginType);
        $instance->setInstanceId($instanceId);
        $this->pluginManager->prepPluginInstance($instance, $this->getEvent());

        $pluginView = $instance->getView();
        $body = $this->viewRenderer->render($pluginView);
        $pluginHtml = $this->viewRenderer->plugin('headScript')
            . $this->viewRenderer->plugin('headLink')
            . $body;

        $jsonModel = new \Zend\View\Model\JsonModel();
        $jsonModel->setVariables(
            array(
                'display' => $pluginHtml,
                'js' => $instance->getAdminEditJs(),
                'css' => $instance->getAdminEditCss()
            )
        );

        return $jsonModel;
    }

    public function savePageAction()
    {
        $this->adminSaveInit();
        $postedData = $this->getPageSaveData();

        $oldRevId = $this->pageRevision->getPageRevId();
        $stagedRevision = $this->page->getStagedRevision();

        if (!empty($stagedRevision)) {
            $stagedId = $stagedRevision->getPageRevId();
        } else {
            $stagedId = null;
        }

        /** @var \Rcm\Entity\PageRevision $newRevision */
        $newRevision = clone $this->pageRevision;
        $newRevision->clearPluginInstances();
        $newRevision->setAuthor($this->loggedInUser->getFullName());

        $newRevision = $this->processPostedInstances($postedData, $newRevision);

        if ($stagedId == $oldRevId) {
            $this->page->setStagedRevision($newRevision);

            $this->entityMgr->persist($this->page);
            $this->entityMgr->persist($newRevision);
            $this->entityMgr->flush();
        }

        if ($this->page->getPageType() != 'n') {
            return $this->redirect()->toRoute(
                'contentManagerWithPageType',
                array(
                    'pageType' => $this->page->getPageType(),
                    'page' => $this->page->getName(),
                    'language' => $this->siteInfo->getLanguage()->getLanguage(),
                    'revision' => $newRevision->getPageRevId()
                )
            )->setStatusCode(301);
        } else {
            return $this->redirect()->toRoute(
                'contentManager',
                array(
                    'page' => $this->page->getName(),
                    'language' => $this->siteInfo->getLanguage()->getLanguage(),
                    'revision' => $newRevision->getPageRevId()
                )
            )->setStatusCode(301);
        }
    }

    public function publishPageAction()
    {
        $this->adminSaveInit();
        $entityMgr = $this->entityMgr;

        $this->page->setCurrentRevision($this->pageRevision);

        $staged = $this->page->getStagedRevision();

        if (!empty($staged)
            && $this->pageRevision == $this->page->getStagedRevision()
        ) {
            $staged->unStageRevision();
            $this->page->removedStagedRevistion();
            $entityMgr->persist($staged);
        }


        $entityMgr->persist($this->page);
        $entityMgr->flush();


        if ($this->page->getPageType() != 'n') {
            return $this->redirect()->toRoute(
                'contentManagerWithPageType',
                array(
                    'pageType' => $this->page->getPageType(),
                    'page' => $this->page->getName(),
                    'language' => $this->siteInfo->getLanguage()->getLanguage(),
                    'revision' => $this->pageRevision->getPageRevId()
                )
            )->setStatusCode(301);
        } else {
            return $this->redirect()->toRoute(
                'contentManager',
                array(
                    'page' => $this->page->getName(),
                    'language' => $this->siteInfo->getLanguage()->getLanguage(),
                    'revision' => $this->pageRevision->getPageRevId()
                )
            )->setStatusCode(301);
        }
    }

    public function stagePageAction()
    {
        $this->adminSaveInit();

        $entityMgr = $this->entityMgr;

        /** @var \Rcm\Entity\Page $page */
        $page = $this->page;

        /** @var \Rcm\Entity\PageRevision $oldStagedRevision */
        $oldStagedRevision = $page->getStagedRevision();

        if (!empty($oldStagedRevision)) {
            $oldStagedRevision->unStageRevision();
            $entityMgr->persist($oldStagedRevision);
        }

        $page->setStagedRevision($this->pageRevision);


        $entityMgr->persist($page);
        $entityMgr->flush();


        if ($this->page->getPageType() != 'n') {
            return $this->redirect()->toRoute(
                'contentManagerWithPageType',
                array(
                    'pageType' => $this->page->getPageType(),
                    'page' => $this->page->getName(),
                    'language' => $this->siteInfo->getLanguage()->getLanguage(),
                )
            )->setStatusCode(301);
        } else {
            return $this->redirect()->toRoute(
                'contentManager',
                array(
                    'page' => $page->getName(),
                    'language' => $this->siteInfo->getLanguage()->getLanguage()
                )
            )->setStatusCode(301);
        }
    }

    public function createBlankPageAction()
    {
        $this->ensureAdminIsLoggedIn();
        $config = $this->config;

        $errors = $config['reliv']['createBlankPagesErrors'];

        $pageUrl = $this->getRequest()->getQuery()->get('pageUrl');
        $pageName = $this->getRequest()->getQuery()->get('pageName');
        $pageLayout = $this->getRequest()->getQuery()->get('selectedLayout');

        if (empty($pageName) || empty($pageUrl) || empty($pageLayout)) {
            $return['error'] = $errors['missingItems'];
            echo json_encode($return);
            exit;
        }

        $em = $this->entityMgr;
        $page = $this->siteInfo->getPageByName($pageUrl);

        if (!empty($page)) {
            $return['error'] = $errors['pageExists'];
            echo json_encode($return);
            exit;
        }

        $pageManager = new \Rcm\Model\PageFactory($this->entityMgr);
        $pageManager->createPage(
            $pageUrl,
            $this->loggedInUser->getFullName(),
            $pageName,
            '',
            '',
            $pageLayout,
            $this->siteInfo,
            null
        );

        $redirectUrl = $this->getPageUrl($pageUrl);

        $return['pageOk'] = 'Y';
        $return['redirect'] = $redirectUrl . '?rcmShowLayoutEditor=Y';

        echo json_encode($return);
        exit;
    }

    public function createSiteAction()
    {
        $this->entityMgr->getConnection()->getConfiguration()->setSQLLogger(null);
        $config = $this->config;
        $errors = $config['reliv']['createSiteErrors'];

        $siteCountry = $this->getRequest()->getQuery()->get('country');
        $siteLanguage = $this->getRequest()->getQuery()->get('language');
        $siteDomain = $this->getRequest()->getQuery()->get('domain');
        $siteToClone = $this->getRequest()->getQuery()->get('siteToClone');

        if (empty($siteCountry) || empty($siteLanguage) || empty($siteDomain)) {
            $return['error'] = $errors['missingItems'];
            echo json_encode($return);
            exit;
        }

        /** @var \Rcm\Entity\Country $countryEntity */
        $countryEntity = $this->entityMgr->getRepository('\Rcm\Entity\Country')
            ->findOneBy(
                array(
                    'iso3' => $siteCountry
                )
            );

        if (empty($countryEntity)) {
            $return['error'] = $errors['countryNotFound'];
            echo json_encode($return);
            exit;
        }

        /** @var \Rcm\Entity\Language $languageEntity */
        $languageEntity = $this->entityMgr->getRepository(
            '\Rcm\Entity\Language'
        )->findOneBy(
                array(
                    'languageId' => $siteLanguage
                )
            );

        if (empty($languageEntity)) {
            $return['error'] = $errors['languageNotFound'];
            echo json_encode($return);
            exit;
        }

        if (!$this->isDomainValid($siteDomain)) {
            $return['error'] = $errors['domainInvalid'];
            echo json_encode($return);
            exit;
        }

        $domainEntity = new Domain();
        $domainEntity->setDefaultLanguage($languageEntity);
        $domainEntity->setDomainName($siteDomain);
        $this->entityMgr->persist($domainEntity);

        if (empty($siteToClone)) {
            $return['error'] = $errors['newSiteNotImplemented'];
            echo json_encode($return);
            exit;
        }

        /** @var \Rcm\Entity\Site $siteToCloneEntity */
        $siteToCloneEntity = $this->entityMgr->getRepository('\Rcm\Entity\Site')
            ->findOneBy(
                array(
                    'siteId' => $siteToClone
                )
            );

        if (empty($siteToCloneEntity)) {
            $return['error'] = $errors['siteNotFound'];
            echo json_encode($return);
            exit;
        }

        $newSite = clone $siteToCloneEntity;
        $newSite->setDomain($domainEntity);
        $newSite->setCountry($countryEntity);
        $newSite->setLanguage($languageEntity);

        $this->entityMgr->persist($newSite);
        $this->entityMgr->flush();

        $newSiteId = $newSite->getSiteId();

        $this->entityMgr->clear();

        $query = $this->entityMgr->createQueryBuilder()
            ->select('i.instanceId, i.previousEntity')
            ->from('\Rcm\Entity\Site', 'site')
            ->join('site.pages', 'page')
            ->join('page.currentRevision', 'revision')
            ->join('revision.pluginInstances', 'pluginWrapper')
            ->join('pluginWrapper.instance', 'i')
            ->where('site.siteId = :siteId')
            ->setParameter('siteId', $newSiteId);

        $instances = $query->getQuery()->getArrayResult();

        $done = array();

        foreach ($instances as $instance) {

            if (in_array($instance['instanceId'], $done)) {
                continue;
            }

            /** @var DoctrineJsonInstanceConfig $config */
            $check = $this->entityMgr
                ->getRepository('RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig')
                ->findOneBy(array('instanceId' => $instance['instanceId']));

            if (!empty($check)) {
                continue;
            }

            /** @var DoctrineJsonInstanceConfig $config */
            $config = $this->entityMgr
                ->getRepository('RcmDoctrineJsonPluginStorage\Entity\DoctrineJsonInstanceConfig')
                ->findOneBy(array('instanceId' => $instance['previousEntity']));

            if (empty($config)) {
                continue;
            }

            $newConfig = new DoctrineJsonInstanceConfig();
            $newConfig->setInstanceId($instance['instanceId']);
            $newConfig->setConfig($config->getConfig());

            $this->entityMgr->persist($newConfig);
            $this->entityMgr->flush();
            $this->entityMgr->clear();

            $done[] = $instance['instanceId'];
        }

        $return['dataOk'] = 'Y';
        $return['redirect'] = '//' . $domainEntity->getDomainName();

        echo json_encode($return);
        exit;
    }

    public function newFromTemplateAction()
    {
        $pageUrl = $this->getRequest()->getQuery()->get('pageUrl');
        $pageName = $this->getRequest()->getQuery()->get('pageName');
        $pageRevision = $this->getRequest()->getQuery()->get('revision');

        if ($pageRevision < 0) {
            $this->createBlankPageAction();
        }

        $this->savePageAs($pageUrl, $pageRevision, $pageName);
    }

    public function saveAsTemplateAction()
    {
        $pageUrl = $this->getRequest()->getQuery()->get('pageName');
        $pageRevision = $this->getRequest()->getQuery()->get('revision');
        $this->savePageAs($pageUrl, $pageRevision, '', 't');
    }

    protected function savePageAs(
        $pageUrl,
        $pageRevision,
        $pageTitle = '',
        $pageType = 'n'
    )
    {
        $this->ensureAdminIsLoggedIn();
        $config = $this->config;
        $pageManager = new \Rcm\Model\PageFactory($this->entityMgr);

        $errors = $config['reliv']['saveAsTemplateErrors'];

        if (empty($pageUrl) || empty($pageRevision)) {
            $return['error'] = $errors['missingItems'];
            echo json_encode($return);
            exit;
        }

        $em = $this->entityMgr;
        $page = $this->getPageByName($pageUrl, $pageType);

        if (!empty($page)) {
            $return['error'] = $errors['pageExists'];
            echo json_encode($return);
            exit;
        }

        $repo = $em->getRepository('\Rcm\Entity\PageRevision');

        /** @var \Rcm\Entity\PageRevision $currentRevision */
        $currentRevision = $repo->findOneBy(
            array('pageRevId' => $pageRevision)
        );

        if (empty($currentRevision)) {
            $return['error'] = $errors['revisionNotFound'];
            echo json_encode($return);
            exit;
        }

        $currentInstances = array();
        $instances = $currentRevision->getRawPluginInstances();

        foreach ($instances as $instance) {
            $currentInstances[] = $instance;
        }

        if (empty($pageTitle)) {
            $pageTitle = $currentRevision->getPageTitle();
        }

        $pageManager->createPage(
            $pageUrl,
            $this->loggedInUser->getFullName(),
            $pageTitle,
            $currentRevision->getDescription(),
            $currentRevision->getKeywords(),
            $currentRevision->getPageLayout(),
            $this->siteInfo,
            $currentInstances,
            $pageType
        );

        $redirectUrl = $this->getPageUrl($pageUrl, $pageType);

        $return['dataOk'] = 'Y';
        $return['redirect'] = $redirectUrl;

        echo json_encode($return);
        exit;
    }

    private function getPageSaveData()
    {
        if (empty($_POST)
            || empty($_POST['saveData'])
        ) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'No Page name, Page Revision, or no Save data sent'
            );
        }

        $postedData = json_decode($_POST['saveData'], true);

        if ($postedData == null) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'Invalid data sent.  Data sent must be a json string'
            );
        }

        if (empty($postedData)) {
            return false;
        }

        return $postedData;
    }

    private function getNewPluginInstance(
        \Rcm\Entity\PagePluginInstance $currentInstance
    )
    {
        $newInstance = clone $currentInstance;
        $newActualInstance = clone $currentInstance->getInstance();
        $newInstance->setInstance($newActualInstance);

        return $newInstance;
    }

    private function savePluginAssets(
        $postedAssets,
        \Rcm\Entity\PluginInstance $newInstance
    )
    {

        if (empty($postedAssets)) {
            return;
        }

        $assets = array();

        foreach ($postedAssets as $url) {
            $url = strtolower($url);

            if (
                !preg_match("/^#/", $url)
                && !preg_match("/^javascript:/", $url)
                && !empty($url)
            ) {
                //If we haven't already have this asset
                if (empty($assets[$url])) {
                    //Look in DB for the asset for this url
                    /** @var \Rcm\Entity\PluginAsset $assetEntity */

                    $repo = $this->entityMgr
                        ->getRepository('\Rcm\Entity\PluginAsset');
                    $assetEntity = $repo->findOneByurl($url);

                    $assets[$url] = $assetEntity;
                    //Create a new asset
                    if (!$assets[$url]) {
                        $assets[$url] = new PluginAsset($url);
                    }
                }
                //Add our current plugin instance to the asset
                $assets[$url]->addPluginInstance($newInstance);

                $this->entityMgr->persist($assets[$url]);
            }
        }

        $this->entityMgr->flush();

        return $assets;
    }

    private function processPostedInstances(
        $postedData,
        \Rcm\Entity\PageRevision $newRev
    )
    {

        foreach ($postedData as $postedInstanceId => $data) {
            if ($postedInstanceId == 'undefined') {
                continue;
            } elseif ($postedInstanceId == 'main') {
                $this->processMainPageData($data, $newRev);
                unset($postedData['main']);
            } else {
                $this->processPostedInstance($postedInstanceId, $data, $newRev);
            }
        }

        //Check for deleted Plugins -- Must be able to save blank pages
        $pageRev = $this->pageRevision;
        $allInstancesInOldRev = $pageRev->getRawPluginInstances();

        foreach ($allInstancesInOldRev as $instance) {
            $instanceId = $instance->getInstanceId();
            if (!isset($postedData[$instanceId])) {
                $newRev->setIsDirty(true);
            }
        }

        if ($newRev->getIsDirty()) {
            $entityMgr = $this->entityMgr;
            $entityMgr->persist($newRev);
            $entityMgr->flush();
            return $newRev;
        } else {
            return $this->pageRevision;
        }
    }

    private function processMainPageData(
        $data,
        \Rcm\Entity\PageRevision $newRev
    )
    {

        if (!empty($data['metaTitle'])) {
            $newRev->setPageTitle($data['metaTitle']);
        }

        if (!empty($data['metaDesc'])) {
            $newRev->setDescription($data['metaDesc']);
        }

        if (!empty($data['metaKeyWords'])) {
            $newRev->setKeywords($data['metaKeyWords']);
        }

        //Check MD5
        $newMD5 = md5(serialize($data));
        $newRev->setMd5($newMD5);

        $oldMD5 = $this->pageRevision->getMd5();

        if ($newMD5 != $oldMD5) {
            $newRev->setIsDirty(true);
        }

        return;
    }

    private function processPostedInstance(
        $instanceId,
        $data,
        \Rcm\Entity\PageRevision $newRev
    )
    {
        //Get Entity Manager
        $entityMgr = $this->entityMgr;

        //Get Current Page Revision
        $pageRev = $this->pageRevision;

        //Set Instance To Dirty
        $instanceDirty = false;

        //Check to see if Instance is new
        if ($instanceId < 0) {
            $currentInstance = $this->processNewPostedInstance($data);
        } else {
            $currentInstance = $pageRev->getInstanceById($instanceId);

            if (empty($currentInstance)) {
                $repo = $entityMgr->getRepository('\Rcm\Entity\PluginInstance');
                $actual = $repo->findOneBy(array('instanceId' => $instanceId));
                $currentInstance = $this->processNewPostedInstance($data);
                $currentInstance->setInstance($actual);
            }

        }

        //Fail if no current instance found
        if (empty($currentInstance)) {
            return false;
        }

        //Get A New Plugin Instance For Saving
        /** @var \Rcm\Entity\PagePluginInstance $newPluginInstance */
        $newPluginInstance = $this->getNewPluginInstance($currentInstance);

        //Get Layout Container
        $currentContainer = $currentInstance->getLayoutContainer();

        if ($data['container'] != $currentContainer) {
            $newPluginInstance->setLayoutContainer($data['container']);
            $instanceDirty = true;
        }

        $renderOrderNumber = $currentInstance->getRenderOrderNumber();
        $renderWidth = $currentInstance->getWidth();
        $renderHeight = $currentInstance->getHeight();
        $renderFloat = $currentInstance->getDivFloat();

        if ($data['order'] != $renderOrderNumber) {
            $newPluginInstance->setRenderOrderNumber($data['order']);
            $instanceDirty = true;
        }

        if (empty($data['pluginWidth']) && !empty($renderWidth)) {
            $newPluginInstance->setWidth(null);
            $instanceDirty = true;
        } elseif (
            !empty($data['pluginWidth']) && $data['pluginWidth'] != $renderWidth
        ) {
            $newPluginInstance->setWidth($data['pluginWidth']);
            $instanceDirty = true;
        }

        if (empty($data['pluginHeight']) && !empty($renderHeight)) {
            $newPluginInstance->setHeight(null);
            $instanceDirty = true;
        } elseif (!empty($data['pluginHeight'])
            && $data['pluginHeight'] != $renderHeight
        ) {
            $newPluginInstance->setHeight($data['pluginHeight']);
            $instanceDirty = true;
        }

        if ($data['pluginFloat'] != $renderFloat) {
            $newPluginInstance->setDivFloat($data['pluginFloat']);
            $instanceDirty = true;
        }

        if (!empty($data['pluginData'])) {
            $md5s = $this->getMd5(
                $currentInstance,
                $data['pluginData']
            );
        } else {
            $md5s = $this->getMd5(
                $currentInstance
            );
        }

        //Check MD5's
        if (($md5s['current'] == $md5s['new'])
            || (empty($data['pluginData']))
        ) {
            $newPluginInstance->setInstance($currentInstance->getInstance());
        } else {
            $newPluginInstance->getInstance()->setMd5($md5s['new']);

            if (!empty($data['assets'])) {
                $newPluginInstance->getInstance()->setAssets(
                    $this->getAssets(
                        $data['assets'],
                        $newPluginInstance->getInstance()
                    )
                );
            }

            $newPluginInstance->getInstance()
                ->setPreviousEntity($currentInstance->getInstance());

            $this->entityMgr->persist($newPluginInstance);
            $this->entityMgr->persist($newPluginInstance->getInstance());

            $this->entityMgr->flush();

            $this->pluginManager->savePlugin(
                $newPluginInstance->getInstance(),
                $data['pluginData']
            );

            $instanceDirty = true;
        }

        if ($newPluginInstance->getInstance()->isSiteWide()
            && $newPluginInstance->getInstance()->getDisplayName()
            != $data['pluginDisplayName']
        ) {
            $newPluginInstance->getInstance()->setDisplayName(
                $data['pluginDisplayName']
            );
            $this->entityMgr->persist($newPluginInstance);
            $this->entityMgr->persist($newPluginInstance->getInstance());
            $this->entityMgr->flush();
            $instanceDirty = true;
        }


        if ($newPluginInstance->getInstance()->isSiteWide()
            && $instanceDirty === true
        ) {

            $newPluginInstance->getInstance()->setDisplayName(
                $data['pluginDisplayName']
            );

            $entityMgr->getConnection()->update(
                'rcm_page_plugin_instances',
                array('instance_id' => $newPluginInstance->getInstanceId()),
                array('instance_id' => $currentInstance->getInstanceId())
            );

            $entityMgr->getConnection()->update(
                'rcm_sites_instances',
                array('instance_id' => $newPluginInstance->getInstanceId()),
                array('instance_id' => $currentInstance->getInstanceId())
            );

            $newRev->addInstance($newPluginInstance);
            return null;

        }

        //Check for new sitewide
        if (!$currentInstance->getInstance()->isSiteWide()
            && $data['siteWide'] == 'Y'
        ) {
            $newPluginInstance->getInstance()->setSiteWide();
            $newPluginInstance->getInstance()->setDisplayName(
                $data['pluginDisplayName']
            );
            $this->siteInfo->addSiteWidePlugin(
                $newPluginInstance->getInstance()
            );
            $this->entityMgr->persist($this->siteInfo);
            $this->entityMgr->flush();
            $instanceDirty = true;
        }

        $this->entityMgr->persist($newPluginInstance);
        $this->entityMgr->persist($newPluginInstance->getInstance());

        if ($instanceDirty === false) {
            $newRev->addInstance($currentInstance);
            return null;
        }

        $newRev->addInstance($newPluginInstance);
        $newRev->setIsDirty(true);


        return null;

    }

    /**
     * @param $instanceData
     *
     * @return \Rcm\Entity\PagePluginInstance
     */
    private function processNewPostedInstance(
        $instanceData
    )
    {
        $pagePluginInstance = new \Rcm\Entity\PagePluginInstance();
        $pagePluginInstance->setRenderOrderNumber(0);
        $pagePluginInstance->setLayoutContainer(0);
        $newPluginInstance = new \Rcm\Entity\PluginInstance();

        $newPluginInstance->setPlugin($instanceData['pluginName']);
        $pagePluginInstance->setInstance($newPluginInstance);


        return $pagePluginInstance;
    }

    private function getMd5(
        \Rcm\Entity\PagePluginInstance $currentInstance,
        $instanceData = array()
    )
    {
        $return = array(
            'current' => $currentInstance->getInstance()->getMd5(),
            'new' => md5(serialize($instanceData))
        );

        return $return;
    }

    protected function getClonedPageRevision(\Rcm\Entity\PageRevision $revision)
    {
        $newRevision = clone $revision;
        $newRevision->setPageRevId(null);

        foreach ($revision->getPluginInstances() as $instance) {
            $newInstance = clone $instance;
            $newRevision->addInstance($instance);
        }

        return $newRevision;
    }

    protected function isDomainValid($domain)
    {
        $validator = new \Zend\Validator\Hostname();
        if (!$validator->isValid($domain)) {
            return false;
        }

        $domainCheck = $this->entityMgr->createQuery(
            '
                        SELECT COUNT(d)
                        FROM \Rcm\Entity\Domain d
                        WHERE d.domain = :domain
                    '
        )->setParameter('domain', $domain)->getSingleScalarResult();

        if ($domainCheck > 0) {
            return false;
        }

        return true;
    }
}