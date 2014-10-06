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
            $sites[] = [
                'siteId' => $site->getSiteId(),
                'domain' => $domain,
                'active' => $site->getStatus() == 'A'
            ];
        }
        return new JsonModel($sites);
    }
} 