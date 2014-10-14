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
//            if ($site === reset($site))
//                echo 'FIRST ELEMENT!'.$site;

//            $temp = $site->getStatus();
//            echo $temp;
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
//        var_dump($data);
        // Check if siteId is valid
        // Store value to make site disabled


        //CREATE RESOURCE ID

        //ACCESS CHECK
//        if (!$this->rcmUserIsAllowed('sites', 'admin', 'RcmAdmin')) {
//            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
//            return $this->getResponse();
//        }
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

        $routeMatch = $this->getEvent()->getRouteMatch();
        $status = $routeMatch->getParam('active');

        echo 'status = '.$status;
        $site = $siteManager->getSiteById($siteId);

        if ($status == 'D') {
            $site->setStatus('D');
        }
        if ($status == 'A')
            $site->setStatus('A');

        $em = $siteManager->getSiteRepo()->getDoctrine();

        $em->persist($site);
        $em->flush();

        return new JsonModel(
            array(
                $site->getSiteId(),
                $site->getStatus()
            )
        );
    }

}