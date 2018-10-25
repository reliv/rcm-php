<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\ResourceName;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

/**
 * Class ApiAdminCountryController
 *
 * API for Rcm Country
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
class ApiAdminCountryController extends ApiAdminBaseController
{

    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(RcmUserService::class);

        //ACCESS CHECK
        if (!$rcmUserService->isAllowed(
            ResourceName::RESOURCE_SITES,
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        /** @var \Rcm\Repository\Country $repo */
        $repo = $this->getEntityManager()->getRepository(\Rcm\Entity\Country::class);

        try {
            $results = $repo->findBy([], ['countryName' => 'ASC']);
        } catch (\Exception $e) {
            return new ApiJsonModel(null, 1, 'An error occurred will getting countries.');
        }

        return new ApiJsonModel($results, 0, 'Success');
    }
}
