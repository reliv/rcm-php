<?php

namespace RcmAdmin\Controller;

use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\InputFilter\SiteDuplicateInputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ApiAdminSitesCloneController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiAdminSitesCloneController extends ApiAdminManageSitesController
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        parent::__construct($serviceLocator);
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
        /* ACCESS CHECK */
        if (!$this->isAllowed(ResourceName::RESOURCE_SITES, 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }
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

        $siteManager = $this->getSiteManager();

        try {
            $data = $siteManager->prepareSiteData($data);
            /** @var \Rcm\Repository\Domain $domainRepo */
            $domainRepo = $this->getEntityManager()->getRepository(
                \Rcm\Entity\Domain::class
            );

            $domain = $domainRepo->createDomain(
                $data['domainName'],
                $this->getCurrentUserId(),
                'Create new domain in ' . get_class($this),
                null,
                false
            );
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository(\Rcm\Entity\Site::class);

        /** @var \Rcm\Entity\Site $existingSite */
        $existingSite = $siteRepo->find($data['siteId']);

        if (empty($existingSite)) {
            return new ApiJsonModel(null, 1, "Site {$data['siteId']} not found.");
        }

        try {
            $copySite = $siteManager->copySiteAndPopulate(
                $existingSite,
                $domain,
                $data,
                true
            );
        } catch (\Exception $exception) {
            // Remove domain if error occurs
            if ($entityManager->contains($domain)) {
                $entityManager->remove($domain);
            }
            throw $exception;
        }

        return new ApiJsonModel($copySite, 0, 'Success');
    }
}
