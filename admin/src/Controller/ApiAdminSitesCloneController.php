<?php

namespace RcmAdmin\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Rcm\Acl\GetCurrentUser;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Http\NotFoundResponseJsonZf2;
use Rcm\Http\Response;
use Rcm\SecureRepo\SiteSecureRepo;
use Rcm\View\Model\ApiJsonModel;
use Rcm\Http\NotAllowedResponseJsonZf2;
use RcmAdmin\InputFilter\SiteDuplicateInputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

class ApiAdminSitesCloneController extends ApiAdminBaseController
{
    protected $siteSecureRepo;
    protected $entityManager;
    protected $getCurrentUser;

    public function __construct(
        SiteSecureRepo $siteSecureRepo,
        EntityManager $entityManager,
        GetCurrentUser $getCurrentUser
    ) {
        $this->siteSecureRepo = $siteSecureRepo;
        $this->entityManager = $entityManager;
        $this->getCurrentUser = $getCurrentUser;
    }

    /**
     * create - Clone a site
     *
     * @param array $data - see buildSiteApiResponse()
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     * @throws \Exception
     */
    public function create($data)
    {
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        /* */
        $inputFilter = new SiteDuplicateInputFilter();
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

        $user = $this->getCurrentUser->__invoke();

        if ($user === null) {
            return new NotFoundResponseJsonZf2();
        }

        $userId = $user->getId();

//        try {
        $data = $this->siteSecureRepo->prepareSiteData($data);
//            /** @var \Rcm\Repository\Domain $domainRepo */
//            $domainRepo = $this->entityManager->getRepository(
//                \Rcm\Entity\Domain::class
//            );
//
//            $domain = $domainRepo->createDomain(
//                $data['domainName'],
//                $userId,
//                'Create new domain in ' . get_class($this),
//                null,
//                true
//            );
//        } catch (NotAllowedException $e) {
//            return new NotAllowedResponseJsonZf2();
//        } catch (\Exception $e) {
//            return new ApiJsonModel(null, 1, $e->getMessage());
//        }

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository(\Rcm\Entity\Site::class);

        /** @var \Rcm\Entity\Site $existingSite */
        $existingSite = $siteRepo->find($data['siteId']);

        if (empty($existingSite)) {
            return new NotFoundResponseJsonZf2();
        }

        try {
            $copySite = $this->siteSecureRepo->duplicateAndUpdate(
                $existingSite,
                $data['domainName'],
                $data
            );
        } catch (NotAllowedException $e) {
            throw $e;
            return new NotAllowedResponseJsonZf2();
        }
//        } catch (\Exception $exception) {
//            // Remove domain if error occurs
//            if ($entityManager->contains($domain)) {
//                $entityManager->remove($domain);
//            }
//            throw $exception;
//        }

        return new ApiJsonModel($copySite, 0, 'Success');
    }
}
