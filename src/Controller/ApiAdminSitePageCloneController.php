<?php

namespace RcmAdmin\Controller;

use Rcm\Entity\Page;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\Entity\SitePageApiResponse;
use RcmAdmin\InputFilter\SitePageDuplicateInputFilter;

/**
 * Class ApiAdminSitePageCloneController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiAdminSitePageCloneController extends ApiAdminSitePageController
{
    /**
     * Constructor.
     *
     * @param \Interop\Container\ContainerInterface|\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        parent::__construct($serviceLocator);
    }

    /**
     * create
     *
     * @param mixed $data
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function create($data)
    {
        //ACCESS CHECK
        if (!$this->rcmIsAllowed('sites', 'admin')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }

        $siteId = $this->getRequestSiteId();

        $site = $this->getSite($siteId);

        if (empty($site)) {
            return new ApiJsonModel(
                null,
                1,
                "Site was not found with id {$siteId}."
            );
        }

        // // //
        $inputFilter = new SitePageDuplicateInputFilter();

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new ApiJsonModel(
                [],
                1,
                'Some values are missing or invalid for page duplication.',
                $inputFilter->getMessages()
            );
        }

        $data = $inputFilter->getValues();

        $destinationSite = $this->getSite($data['destinationSiteId']);

        if (empty($destinationSite)) {
            return new ApiJsonModel(
                null,
                1,
                "Destination site was not found with id {$data['destinationSiteId']}."
            );
        }

        $page = $this->getPage($site, $data['pageId']);

        $newPage = new Page();

        $newPage->populate($data);

        if (empty($page)) {
            return new ApiJsonModel(
                null,
                1,
                "Source page was not found with id {$data['pageId']}."
            );
        }

        if ($this->hasPage($destinationSite, $newPage->getName(), $newPage->getPageType())) {
            return new ApiJsonModel(
                null,
                1,
                'Page already exists, duplicates cannot be created'
            );
        }

        // force author to current user
        $newPage->setAuthor($this->getCurrentAuthor());

        try {
            $newPage = $this->getPageRepo()->copyPage(
                $destinationSite,
                $page,
                $newPage->toArray(),
                null,
                true
            );
        } catch (\Exception $e) {
            return new ApiJsonModel(
                null,
                1,
                $e->getMessage()
            );
        }

        $apiResponse = new SitePageApiResponse();

        $apiResponse->populate($newPage->toArray());

        return new ApiJsonModel($apiResponse, 0, "Success: Duplicated page to site {$data['destinationSiteId']}");
    }
}
