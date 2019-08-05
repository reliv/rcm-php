<?php

namespace RcmAdmin\Controller;

use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use Rcm\Entity\Page;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmAdmin\Entity\SitePageApiResponse;
use RcmAdmin\InputFilter\SitePageDuplicateInputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ApiAdminSitePageCloneController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiAdminSitePageCloneController extends ApiAdminSitePageController
{
    /**
     * Constructor.
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        parent::__construct($serviceLocator);
    }

    /**
     * create
     *
     * @param array $data
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function create($data)
    {
        //ACCESS CHECK
        if (!$this->getRcmUserService()->isAllowed(ResourceName::RESOURCE_SITES, 'admin')) {
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

        if (empty($page)) {
            return new ApiJsonModel(
                null,
                1,
                "Source page was not found with id {$data['pageId']}."
            );
        }

        if ($this->hasPage($destinationSite, $page->getName(), $page->getPageType())) {
            return new ApiJsonModel(
                null,
                1,
                'Page already exists, duplicates cannot be created'
            );
        }

        if (empty($page->getPublishedRevision())) {
            return new ApiJsonModel(
                null,
                1,
                'Cannot duplicate an unpublished revision'
            );
        }

        $this->pageMutationService->duplicatePage(
            $page,
            $destinationSite->getSiteId(),
            $page->getName()
        );

//        $apiResponse = new SitePageApiResponse($newPage);

//        $apiResponse->populate($newPage->toArray());

        return new ApiJsonModel([], 0, "Success: Duplicated page to site {$data['destinationSiteId']}");
    }
}
