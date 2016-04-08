<?php

namespace RcmAdmin\Controller;

use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\InputFilter\SiteDuplicateInputFilter;
use Zend\View\Model\JsonModel;

/**
 * ApiAdminSitesCloneController
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
class ApiAdminSitesCloneController extends ApiAdminManageSitesController
{
    /**
     * create - Clone a site
     *
     * @param array $data - see buildSiteApiResponse()
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
                '\Rcm\Entity\Domain'
            );

            $domain = $domainRepo->createDomain(
                $data['domainName']
            );
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, $e->getMessage());
        }

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository('\Rcm\Entity\Site');

        /** @var \Rcm\Entity\Site $existingSite */
        $existingSite = $siteRepo->find($data['siteId']);

        if (empty($existingSite)) {
            return new ApiJsonModel(null, 1, "Site {$data['siteId']} not found.");
        }

        /** @var \Rcm\Entity\Site $newSite */
        $siteData = new Site();

        $siteData->populate($data);

        $copySite = $siteManager->copySite(
            $existingSite,
            $siteData,
            $domain
        );

        return new ApiJsonModel($copySite, 0, 'Success');
    }
}
