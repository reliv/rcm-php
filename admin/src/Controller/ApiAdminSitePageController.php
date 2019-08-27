<?php

namespace RcmAdmin\Controller;

use Interop\Container\ContainerInterface;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\Http\NotFoundResponseJsonZf2;
use Rcm\Http\Response;
use Rcm\RequestContext\RequestContext;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\Entity\SitePageApiResponse;
use RcmAdmin\InputFilter\SitePageCreateInputFilter;
use RcmAdmin\InputFilter\SitePageUpdateInputFilter;
use Rcm\SecureRepo\PageSecureRepo;
use RcmUser\Service\RcmUserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApiAdminSitePageController extends ApiAdminBaseController
{
    /**
     * @var PageSecureRepo
     */
    protected $pageSecureRepo;

    /**
     * Constructor.
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct(
        $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
        /**
         * @var $requestContext ContainerInterface
         */
        $requestContext = $serviceLocator->get(RequestContext::class);
        $this->pageSecureRepo = $requestContext->get(PageSecureRepo::class);
    }


    /**
     * getList
     *
     * @return mixed|ApiJsonModel
     */
    public function getList()
    {
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        try {
            $pages = $this->pageSecureRepo->findPagesBySiteId($this->getRequestSiteId());
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

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
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        try {
            $page = $this->pageSecureRepo->find($id);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        if (empty($page)) {
            return new NotFoundResponseJsonZf2();
        }

        $site = $this->getSite($this->getRequestSiteId());

        if (empty($site)) {
            return new NotFoundResponseJsonZf2();
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
        if ($this->getCurrentUser() === null) {
            return new NotAllowedResponseJsonZf2();
        }

        $siteId = $this->getRequestSiteId();

        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

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
            return new NotFoundResponseJsonZf2();
        }

        $page = $this->getPage($site, $id);

        try {
            $this->pageSecureRepo->updatePublishedVersionOfPage($page, $data);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        $apiResponse = new SitePageApiResponse($page);

        return new ApiJsonModel($apiResponse, 0, 'Success: Page updated.');
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

        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

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
        try {
            $this->pageSecureRepo->depublishPage($page);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        return new ApiJsonModel([true], 0, 'Page deleted');
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
     * @param Site $site
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
}
