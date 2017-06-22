<?php

namespace RcmAdmin\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Interop\Container\ContainerInterface;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\InputFilter\SiteInputFilter;
use RcmUser\Service\RcmUserService;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;

/**
 * ApiAdminManageSitesController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 *
 * @method boolean rcmIsAllowed($resourceId, $privilege = null, $providerId = 'Rcm\Acl\ResourceProvider')
 */
class ApiAdminManageSitesController extends ApiAdminBaseController
{
    /**
     * @param ContainerInterface $serviceLocator
     */
    public function __construct(
        $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * getSiteManager
     *
     * @return \RcmAdmin\Service\SiteManager
     */
    protected function getSiteManager()
    {
        return $this->serviceLocator->get('RcmAdmin\Service\SiteManager');
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
            throw new TrackingException('A valid user is required in ' . static::class);
        }

        return (string)$user->getId();
    }

    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');
        $createQueryBuilder = $siteRepo->createQueryBuilder('site')
            ->select('site')
            ->leftJoin('site.domain', 'domain')
            ->leftJoin('site.country', 'country')
            ->leftJoin('site.language', 'language')
            ->orderBy('domain.domain', 'ASC');

        $query = $createQueryBuilder->getQuery();

        $searchQuery = $this->params()->fromQuery('q');

        if ($searchQuery) {
            $createQueryBuilder->where(
                $createQueryBuilder->expr()->like(
                    'domain.domain',
                    ':searchQuery'
                )
            );
            $query = $createQueryBuilder->getQuery();
            $query->setParameter('searchQuery', $searchQuery . '%');
        }

        $adaptor = new DoctrinePaginator(new ORMPaginator($query));
        $paginator = new Paginator($adaptor);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');
        if ($page) {
            $paginator->setCurrentPageNumber($page);
        }

        $pageSize = (int)$this->params()->fromQuery('page_size');
        if ($pageSize) {
            $paginator->setItemCountPerPage($pageSize);
        }

        $sitesObjects = $paginator->getCurrentItems();

        $sites = [];

        /** @var \Rcm\Entity\Site $site */
        foreach ($sitesObjects as $site) {
            $sites[] = $site->toArray();
        }

        $list['items'] = $sites;
        $list['itemCount'] = $paginator->getTotalItemCount();
        $list['pageCount'] = $paginator->count();
        $list['currentPage'] = $paginator->getCurrentPageNumber();

        return new ApiJsonModel($list, 0, 'Success');
    }

    /**\
     * get
     *
     * @param mixed $id
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id)
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed('sites', 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        // get default site data - kinda hacky, but keeps us to one controller
        if ($id == 'default') {
            $siteManager = $this->getSiteManager();

            $data = $siteManager->getDefaultSiteValues();

            $site = new Site(
                $this->getCurrentUserId(),
                'Get default site values in ' . static::class
            );

            $site->populate($data);

            return new ApiJsonModel($site, 0, 'Success');
        }

        // get current site data - kinda hacky, but keeps us to one controller
        if ($id == 'current') {
            $site = $this->getCurrentSite();

            return new ApiJsonModel($site, 0, 'Success');
        }

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $this->getEntityManager()->getRepository(
            '\Rcm\Entity\Site'
        );

        try {
            $site = $siteRepo->find($id);
        } catch (\Exception $e) {
            return new ApiJsonModel(
                null,
                1,
                "Failed to find site by id ({$id})"
            );
        }
        /* ADDED THIS CHECK TO GET RID OF ERROR. */
        /* NOT SURE WHY TRY CATCH ABOVE ISN'T WORKING */

        if ($site instanceof Site) {
            return new ApiJsonModel($site, 0, 'Success');
        } else {
            return new ApiJsonModel(null, 1, "Failed to find site by id ({$id})");
        }
    }

    /**
     * update @todo - allow update of all properties and filter input
     *
     * @param mixed $siteId
     * @param mixed $data
     *
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function update($siteId, $data)
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        if (!is_array($data)) {
            throw new \Exception('Invalid data format');
        }

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        if (!$siteRepo->isValidSiteId($siteId)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }

        /** @var \Rcm\Entity\Site $site */
        $site = $siteRepo->findOneBy(['siteId' => $siteId]);

        if ($data['status'] == 'D') {
            $site->setStatus('D');
        }
        if ($data['status'] == 'A') {
            $site->setStatus('A');
        }

        $entityManager->persist($site);
        $entityManager->flush();

        return new JsonModel($site);
    }

    /**
     * create - Create a site
     *
     * @param array $data
     *
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        /* ACCESS CHECK */
        if (!$this->rcmIsAllowed('sites', 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }
        /* */

        $inputFilter = new SiteInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new ApiJsonModel(
                [],
                1,
                'Some values are missing or invalid.',
                $inputFilter->getMessages()
            );
        }

        $data = $inputFilter->getValues();

        $siteManager = $this->getSiteManager();

        try {
            $data = $siteManager->prepareSiteData($data);
            /** @var \Rcm\Repository\Domain $domainRepo */
            $domainRepo = $this->getEntityManager()->getRepository(
                '\Rcm\Entity\Domain'
            );

            $data['domain'] = $domainRepo->createDomain(
                $data['domainName']
            );
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        /** @var \Rcm\Entity\Site $newSite */
        $newSite = new Site(
            $this->getCurrentUserId(),
            'Create new site in ' . static::class
        );

        $newSite->populate($data);
        // make sure we don't have a siteId
        $newSite->setSiteId(null);

        try {
            $newSite = $siteManager->createSite($newSite);
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        return new ApiJsonModel($newSite, 0, 'Success');
    }
}
