<?php

namespace RcmAdmin\Controller;

use Rcm\Entity\Site;
use Rcm\Tracking\Model\Tracking;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\Entity\SiteApiResponse;

/**
 * ApiAdminCurrentSiteController.
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
 * @method boolean rcmIsAllowed($resourceId, $privilege = null, $providerId = \Rcm\Acl\ResourceProvider::class)
 */
class ApiAdminCurrentSiteController extends ApiAdminBaseController
{
    /**
     * getCurrentSite
     *
     * @return \Rcm\Entity\Site
     */
    protected function getCurrentSite()
    {
        return $this->serviceLocator->get(\Rcm\Service\CurrentSite::class);
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
        $site = $this->getCurrentSite();

        $result = $this->buildSiteApiResponse($site);

        return new ApiJsonModel($result, 0, 'Success');
    }

    /**
     * buildSiteApiResponse
     *
     * @param Site $site
     *
     * @return SiteApiResponse
     */
    protected function buildSiteApiResponse(Site $site)
    {
        $siteApiResponse = new SiteApiResponse(Tracking::UNKNOWN_AUTHOR);

        $siteApiResponse->populateFromObject($site);

        return $siteApiResponse;
    }
}
