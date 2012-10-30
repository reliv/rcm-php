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
     * @package   Main\Application\Controllers\Index
     * @author    Unkown <unknown@relivinc.com>
     * @copyright 2012 Reliv International
     * @license   License.txt New BSD License
     * @version   GIT: <git_id>
     * @link      http://ci.reliv.com/confluence
     */
namespace Rcm\Controller;

use \Rcm\Controller\BaseController,
\Rcm\Entity\PageRevision,
\Rcm\Entity\PluginInstance,
\Rcm\Entity\PluginAsset;

/**
 * Index Controller for the entire application
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
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
class AdminController extends BaseController
{
    public function checkPageNameJsonAction()
    {
        $this->ensureAdminIsLoggedIn();

        $pageUrl = $this->getRequest()->getQuery()->get('pageUrl');
        $pageUrl = urlencode($pageUrl);

        $em = $this->entityMgr;
        $repo = $em->getRepository("\Rcm\Entity\Page");
        $page = $this->siteInfo->getPageByName($pageUrl);

        if (empty($page)) {
            $data['pageOk'] = 'Y';
            echo json_encode($data);
            exit;
        }

        $data['pageOk'] = 'N';
        echo json_encode($data);
        exit;
    }

    public function getNewInstanceAction()
    {
        $this->ensureAdminIsLoggedIn();

        $pluginType = $this->getEvent()->getRouteMatch()->getParam('type');

        $instance = new \Rcm\Entity\PluginInstance();
        $instance->setPlugin($pluginType);
        $instance->setInstanceId(-1);
        $this->pluginManager->prepPluginInstance($instance, $this->getEvent());

        $instance->getView();
        $this->view->setVariable('newInstance', $instance);

        return $this->view;
    }

    public function savePageAction()
    {
        $this->adminSaveInit();
        $this->setConfig();
        $postedData = $this->getPageSaveData();

        /** @var \Rcm\Entity\PageRevision $newRevision  */
        $newRevision = clone $this->pageRevision;
        $newRevision = $this->processPostedInstances($postedData, $newRevision);

        return $this->redirect()->toRoute(
            'contentManager',
            array(
                'page' => $this->page->getName(),
                'language' => $this->siteInfo->getLanguage()->getLanguage(),
                'revision' => $newRevision->getPageRevId()
            )
        )->setStatusCode(301);
    }

    public function publishPageAction()
    {
        $this->adminSaveInit();
        $entityMgr=$this->entityMgr;

        $this->page->setCurrentRevision($this->pageRevision);

        $staged = $this->page->getStagedRevision();

        if (!empty($staged)) {
            $staged->unStageRevision();
            $this->page->removedStagedRevistion();
            $entityMgr->persist($staged);
        }


        $entityMgr->persist($this->page);
        $entityMgr->flush();


        return $this->redirect()->toRoute(
            'contentManager',
            array(
                'page' => $this->page->getName(),
                'language' => $this->siteInfo->getLanguage()->getLanguage()
            )
        )->setStatusCode(301);
    }

    public function stagePageAction()
    {
        $this->adminSaveInit();

        $entityMgr=$this->entityMgr;

        /** @var \Rcm\Entity\Page $page  */
        $page = $this->page;

        /** @var \Rcm\Entity\PageRevision $oldStagedRevision  */
        $oldStagedRevision = $page->getStagedRevision();

        if (!empty($oldStagedRevision)) {
            $oldStagedRevision->unStageRevision();
            $entityMgr->persist($oldStagedRevision);
        }

        $page->setStagedRevision($this->pageRevision);


        $entityMgr->persist($page);
        $entityMgr->flush();


        return $this->redirect()->toRoute(
            'contentManager',
            array(
                'page' => $page->getName(),
                'language' => $this->siteInfo->getLanguage()->getLanguage()
            )
        )->setStatusCode(301);
    }

    public function createBlankPageAction()
    {
        $this->ensureAdminIsLoggedIn();
        $config = $this->getConfig();

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

        $pageManager = new \Rcm\Model\PageFactory();
        $pageManager->setEm($this->entityMgr);
        $pageManager->createPage(
            $pageUrl,
            $this->loggedInUser->getFullName(),
            $pageName,
            '',
            '',
            $pageLayout,
            $this->siteInfo,
            null,
            false
        );

        $redirectUrl = $this->url()->fromRoute(
            'contentManager',
            array(
                'page' => $pageUrl,
                'language' => $this->siteInfo->getLanguage()->getLanguage()
            )
        );

        $return['pageOk'] = 'Y';
        $return['redirect'] = $redirectUrl.'?rcmShowLayoutEditor=Y';

        echo json_encode($return);
        exit;
    }

    public function newFromTemplateAction()
    {
        $pageUrl = $this->getRequest()->getQuery()->get('pageUrl');
        $pageName = $this->getRequest()->getQuery()->get('pageName');
        $pageRevision = $this->getRequest()->getQuery()->get('revision');
        $this->savePageAs($pageUrl, $pageRevision, true, $pageName);
    }

    public function saveAsTemplateAction()
    {
        $pageUrl = $this->getRequest()->getQuery()->get('pageName');
        $pageRevision = $this->getRequest()->getQuery()->get('revision');
        $this->savePageAs($pageUrl, $pageRevision, '', true);
    }

    private function savePageAs($pageUrl, $pageRevision, $pageTitle='', $asTemplate=false)
    {
        $this->ensureAdminIsLoggedIn();
        $config = $this->getConfig();
        $pageManager = new \Rcm\Model\PageFactory();
        $pageManager->setEm($this->entityMgr);

        $errors = $config['reliv']['saveAsTemplateErrors'];

        if (empty($pageUrl) || empty($pageRevision)) {
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

        $repo = $em->getRepository("\Rcm\Entity\PageRevision");

        /** @var \Rcm\Entity\PageRevision $currentRevision  */
        $currentRevision = $repo->findOneBy(array('pageRevId' => $pageRevision));

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
            $asTemplate
        );

        $redirectUrl = $this->url()->fromRoute(
            'contentManager',
            array(
                'page' => $pageUrl,
                'language' => $this->siteInfo->getLanguage()->getLanguage()
            )
        );

        $return['pageOk'] = 'Y';
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

    private function getNewPluginInstance(\Rcm\Entity\PagePluginInstance $currentInstance)
    {
        $newInstance = clone $currentInstance;
        $newActualInstance = clone $currentInstance->getInstance();
        $newInstance->setInstance($newActualInstance);

        return $newInstance;
    }

    private function savePluginAssets(
        $postedAssets,
        \Rcm\Entity\PluginInstance $newInstance
    ) {

        if (empty($postedAssets)) {
            return;
        }

        $assets=array();
        $em = $entityMgr;

        foreach($postedAssets as $url){
            $url=strtolower($url);

            if(
                !preg_match("/^#/", $url)
                &&!preg_match("/^javascript:/", $url)
                &&!empty($url)
            ) {
                //If we haven't already have this asset
                if(empty($assets[$url])){
                    //Look in DB for the asset for this url
                    /** @var \Rcm\Entity\PluginAsset $assetEntity */

                    $repo = $em->getRepository('\Rcm\Entity\PluginAsset');
                    $assetEntity = $repo->findOneByurl($url);

                    $assets[$url] = $assetEntity;
                    //Create a new asset
                    if(!$assets[$url]){
                        $assets[$url] = new PluginAsset($url);
                    }
                }
                //Add our current plugin instance to the asset
                $assets[$url]->addPluginInstance($newInstance);

                $entityMgr->persist($assets[$url]);
            }
        }

        $em->flush();

        return $assets;
    }

    private function processPostedInstances($postedData,
        \Rcm\Entity\PageRevision $newRev
    )
    {
        foreach($postedData as $postedInstanceId => $data) {
            if ($postedInstanceId == 'main') {
                $this->processMainPageData($data, $newRev);
                unset($postedData['main']);
            } else {
                $this->processPostedInstance($postedInstanceId, $data, $newRev);
            }
        }

        //Check for empty page -- Must be able to save blank pages
        if (empty($postedData)) {
            $newRev->setIsDirty(true);
        }

        if ($newRev->getIsDirty()) {
            $entityMgr=$this->entityMgr;
            $entityMgr->persist($newRev);
            $entityMgr->flush();
            return $newRev;
        } else {
            return $this->pageRevision;
        }
    }

    private function processMainPageData($data,
        \Rcm\Entity\PageRevision $newRev
    ) {

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
    ) {

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
        /** @var \Rcm\Entity\PagePluginInstance $newPluginInstance  */
        $newPluginInstance = $this->getNewPluginInstance($currentInstance);

        //Get Layout Container
        $currentContainer = $currentInstance->getLayoutContainer();

        if ($data['container'] != $currentContainer) {
            $newPluginInstance->setLayoutContainer($data['container']);
            $instanceDirty = true;
        }

        $renderOrderNumber = $currentInstance->getRenderOrderNumber();

        if ($data['order'] != $renderOrderNumber) {
            $newPluginInstance->setRenderOrderNumber($data['order']);
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
            && $instanceDirty === true
        ) {

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
    ) {
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
        $instanceData=array()
    ) {
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

}