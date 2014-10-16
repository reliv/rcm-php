<?php
/**
 * SitesApiController.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Controller\Plugin
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Controller;

use Aws\CloudFront\Exception\Exception;
use Rcm\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * SitesApiController
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
 */
class ManageSitesApiController extends AbstractRestfulController
{
    public function getList()
    {
        //ACCESS CHECK
        if (!$this->rcmUserIsAllowed(
            'sites',
            'admin',
            'Rcm\Acl\ResourceProvider'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }
        /**
         * @var $siteManager \Rcm\Service\SiteManager
         */
        $siteManager = $this->getServiceLocator()->get(
            'Rcm\Service\SiteManager'
        );

        $sitesObjects = $siteManager->getAllSites();

        $sites = [];

        foreach ($sitesObjects as $site) {
            $domain = null; //'[no domains found for this site]';

            if (is_object($site->getDomain())) {
                $domain = $site->getDomain()->getDomainName();
            }

            $sites[] = [
                'siteId' => $site->getSiteId(),
                'domain' => $domain,
                'active' => $site->getStatus(),
            ];
        }
        return new JsonModel($sites);
    }

    /**
     * update
     *
     * @param mixed $siteId
     * @param mixed $data
     *
     * @return mixed|JsonModel
     */
    public function update($siteId, $data)
    {
        //ACCESS CHECK
        if (!$this->rcmUserIsAllowed(
            'sites',
            'admin',
            'Rcm\Acl\ResourceProvider'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        if (!is_array($data)) {
            throw new Exception('Invalid data format');
        }
        /**
         * @var $siteManager \Rcm\Service\SiteManager
         */
        $siteManager = $this->getServiceLocator()->get(
            'Rcm\Service\SiteManager'
        );

        if (!$siteManager->isValidSiteId($siteId)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $this->getResponse();
        }

        $site = $siteManager->getSiteById($siteId);

        if ($data['active'] == 'D') {
            $site->setStatus('D');
        }
        if ($data['active'] == 'A') {
            $site->setStatus('A');
        }

        $em = $siteManager->getSiteRepo()->getDoctrine();

        $em->persist($site);
        $em->flush();

        return new JsonModel(
            array()
        );
    }
}