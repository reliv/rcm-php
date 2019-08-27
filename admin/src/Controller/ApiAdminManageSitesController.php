<?php

namespace RcmAdmin\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Interop\Container\ContainerInterface;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\ImmutableHistory\Site\SiteLocator;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\RequestContext\RequestContext;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\View\Model\ApiJsonModel;
use Rcm\Http\NotAllowedResponseJsonZf2;
use RcmAdmin\InputFilter\SiteInputFilter;
use Rcm\SecureRepo\SiteSecureRepo;
use RcmUser\Service\RcmUserService;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
use Rcm\Exception\InputFilterFoundInvalidDataException;

class ApiAdminManageSitesController extends ApiAdminBaseController
{
    protected $siteSecureRepo;

    public function __construct(
        SiteSecureRepo $siteSecureRepo
    ) {
        $this->siteSecureRepo = $siteSecureRepo;
    }

    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        try {
            $list = $this->siteSecureRepo->getList(
                $this->params()->fromQuery('q'),
                (int)$this->params()->fromQuery('page'),
                (int)$this->params()->fromQuery('page_size')
            );
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

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
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        try {
            $site = $this->siteSecureRepo->get($id);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        return new ApiJsonModel($site, 0, 'Success');
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
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        try {
            $site = $this->siteSecureRepo->update($siteId, $data);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

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
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        try {
            $newSite = $this->siteSecureRepo->createSingleFromArray($data);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        } catch (InputFilterFoundInvalidDataException $e) {
            return new ApiJsonModel(
                [],
                1,
                $e->getMainMessage(),
                $e->getMessages()
            );
        }

        return new ApiJsonModel($newSite, 0, 'Success');
    }
}
