<?php

namespace RcmAdmin\Controller;

use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\Entity\SitePageApiResponse;
use RcmAdmin\InputFilter\SitePageCreateInputFilter;
use RcmAdmin\InputFilter\SitePageUpdateInputFilter;
use RcmUser\Service\RcmUserService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ApiAdminSitePageController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiAdminSitePageController extends ApiAdminBaseController
{
    /**
     * Constructor.
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct(
        $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * isAllowed
     *
     * @param $resourceId
     * @param $privilege
     *
     * @return mixed
     */
    protected function isAllowed($resourceId, $privilege)
    {
        $rcmUserService = $this->getServiceLocator()->get(
            'RcmUser\Service\RcmUserService'
        );

        return $rcmUserService->isAllowed(
            $resourceId,
            $privilege
        );
    }

    /**
     * getSitePagesResourceId
     *
     * @param $siteId
     *
     * @return string
     */
    protected function getSitePagesResourceId($siteId)
    {
        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        return $resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId,
            ResourceName::RESOURCE_PAGES
        );
    }

    /**
     * getPageRepo
     *
     * @return \Rcm\Repository\Page
     */
    protected function getPageRepo()
    {
        return $this->getEntityManager()->getRepository(\Rcm\Entity\Page::class);
    }

    /**
     * getSiteRepo
     *
     * @return \Rcm\Repository\Site
     */
    protected function getSiteRepo()
    {
        return $this->getEntityManager()->getRepository(\Rcm\Entity\Site::class);
    }

    /**
     * getSite
     *
     * @param $siteId
     *
     * @return \Rcm\Entity\Site|null
     */
    protected function getSite($siteId)
    {
        try {
            /** @var Site $site */
            $site = $this->getSiteRepo()->findOneBy(['siteId' => $siteId]);
        } catch (\Exception $e) {
            $site = null;
        }

        return $site;
    }

    /**
     * getSite
     *
     * @param Site $site
     *
     * @return \Rcm\Entity\Page|null
     */
    protected function getPage(Site $site, $pageId)
    {
        return $this->getPageRepo()->getSitePage($site, $pageId);
    }

    /**
     * @return RcmUserService
     */
    protected function getRcmUserService()
    {
        return $this->serviceLocator->get(RcmUserService::class);
    }

    /**
     * @return string
     * @throws TrackingException
     */
    protected function getCurrentUserId()
    {
        /** @var RcmUserService $service */
        $service = $this->getRcmUserService();

        $user = $service->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        return (string)$user->getId();
    }

    /**
     * hasPage
     *
     * @param Site   $site
     * @param string $pageName
     * @param string $pageType
     *
     * @return bool
     */
    protected function hasPage(
        $site,
        $pageName,
        $pageType
    ) {
        return $this->getPageRepo()->sitePageExists(
            $site,
            $pageName,
            $pageType
        );
    }

    /**
     * getRequestSiteId
     *
     * @return mixed
     */
    protected function getRequestSiteId()
    {
        $siteId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'siteId',
                'current'
            );

        if ($siteId == 'current') {
            $siteId = $this->getCurrentSite()->getSiteId();
        }

        return (int)$siteId;
    }

    /**
     * getList
     *
     * @return mixed|ApiJsonModel
     */
    public function getList()
    {
        $siteId = $this->getRequestSiteId();

        //ACCESS CHECK
        $sitePagesResource = $this->getSitePagesResourceId($siteId);
        if (!$this->isAllowed('pages', 'read')
            && !$this->isAllowed(
                $sitePagesResource,
                'read'
            )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $site = $this->getSite($siteId);

        if (empty($site)) {
            return new ApiJsonModel(
                null,
                1,
                "Site was not found with id {$siteId}."
            );
        }

        $pages = $site->getPages();

        foreach ($pages as $key => $page) {
            $apiResponse = new SitePageApiResponse($page);

            $pages[$key] = $apiResponse;
        }

        return new ApiJsonModel(
            $pages,
            0,
            'Success'
        );
    }

    /**
     * get
     *
     * @param mixed $id
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id)
    {
        $siteId = $this->getRequestSiteId();

        //ACCESS CHECK
        $sitePagesResource = $this->getSitePagesResourceId($siteId);
        if (!$this->isAllowed('pages', 'read')
            && !$this->isAllowed(
                $sitePagesResource,
                'read'
            )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $site = $this->getSite($siteId);

        if (empty($site)) {
            return new ApiJsonModel(
                null,
                1,
                "Site was not found with id {$siteId}."
            );
        }

        $page = $this->getPage($site, $id);

        if (empty($page)) {
            return new ApiJsonModel(
                null,
                404,
                "Page was not found with id {$id}."
            );
        }

        $apiResponse = new SitePageApiResponse($page);

        return new ApiJsonModel($apiResponse, 0, 'Success');
    }

    /**
     * update
     *
     * @todo Needs data prepare for site and exception message needs scrubbed
     *
     * @param mixed $id
     * @param mixed $data
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function update($id, $data)
    {
        $siteId = $this->getRequestSiteId();

        //ACCESS CHECK
        $sitePagesResource = $this->getSitePagesResourceId($siteId);
        if (!$this->isAllowed('pages', 'edit')
            && !$this->isAllowed(
                $sitePagesResource,
                'edit'
            )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $inputFilter = new SitePageUpdateInputFilter();

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new ApiJsonModel(
                [],
                1,
                'Some values are missing or invalid for page update.',
                $inputFilter->getMessages()
            );
        }

        $data = $inputFilter->getValues();

        // <tracking>
        $data['modifiedByUserId'] = $this->getCurrentUserId();
        $data['modifiedReason'] = 'Update site in ' . get_class($this);

        $site = $this->getSite($siteId);

        if (empty($site)) {
            return new ApiJsonModel(
                null,
                1,
                "Site was not found with id {$siteId}."
            );
        }

        $page = $this->getPage($site, $id);

        try {
            $this->getPageRepo()->updatePage(
                $page,
                $data
            );
        } catch (\Exception $e) {
            return new ApiJsonModel(
                null,
                1,
                $e->getMessage()
            );
        }

        $apiResponse = new SitePageApiResponse($page);

        return new ApiJsonModel($apiResponse, 0, 'Success: Page updated.');
    }

    /**
     * create
     *
     * @param mixed $data
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function create($data)
    {
        $siteId = $this->getRequestSiteId();

        //ACCESS CHECK
        $sitePagesResource = $this->getSitePagesResourceId($siteId);
        if (!$this->isAllowed('pages', 'create')
            && !$this->isAllowed(
                $sitePagesResource,
                'create'
            )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $site = $this->getSite($siteId);

        if (empty($site)) {
            return new ApiJsonModel(
                null,
                1,
                "Site was not found with id {$siteId}."
            );
        }

        // // //
        $inputFilter = new SitePageCreateInputFilter();

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new ApiJsonModel(
                [],
                1,
                'Some values are missing or invalid for page creation.',
                $inputFilter->getMessages()
            );
        }

        $data = $inputFilter->getValues();

        if ($this->hasPage($site, $data['name'], $data['pageType'])) {
            return new ApiJsonModel(
                null,
                1,
                'Page already exists, duplicates cannot be created'
            );
        }

        $user = $this->getCurrentUserTracking();

        $data['createdByUserId'] = $user->getId();
        $data['createdReason'] = 'New page in ' . get_class($this);
        $data['author'] = $user->getName();

        try {
            $page = $this->getPageRepo()->createPage(
                $site,
                $data
            );
        } catch (\Exception $e) {
            return new ApiJsonModel(
                null,
                1,
                $e->getMessage()
            );
        }

        $apiResponse = new SitePageApiResponse($page);

        return new ApiJsonModel($apiResponse, 0, 'Success: Page created');
    }

    /**
     * delete
     *
     * @param string $id
     *
     * @return ApiJsonModel
     */
    public function delete($id)
    {
        $id = (int)$id;
        $siteId = $this->getRequestSiteId();

        //ACCESS CHECK
        $sitePagesResource = $this->getSitePagesResourceId($siteId);
        if (!$this->isAllowed('pages', 'delete')
            && !$this->isAllowed(
                $sitePagesResource,
                'delete'
            )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return new ApiJsonModel(
                null,
                1,
                "Access denied"
            );
        }

        $site = $this->getSite($siteId);

        if (empty($site)) {
            return new ApiJsonModel(
                null,
                1,
                "Site was not found with id {$siteId}."
            );
        }

        $page = $this->getPage($site, $id);

        if (empty($page)) {
            return new ApiJsonModel(
                null,
                404,
                "Page was not found with id {$id}."
            );
        }

        $pageRepo = $this->getPageRepo();

        $result = $pageRepo->setPageDeleted(
            $page,
            $this->getCurrentUserId(),
            'Delete page in ' . get_class($this)
        );

        if (!$result) {
            return new ApiJsonModel([$result], 1, 'Page could not be deleted');
        }

        return new ApiJsonModel([$result], 0, 'Page deleted');
    }
}
