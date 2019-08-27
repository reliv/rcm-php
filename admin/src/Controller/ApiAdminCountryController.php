<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\CountrySecureRepo;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

class ApiAdminCountryController extends ApiAdminBaseController
{
    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        $secureRepo = $this->getServiceLocator()->get(RequestContext::class)
            ->get(CountrySecureRepo::class);

        try {
            $list = $secureRepo->findAll(['countryName' => 'ASC']);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        return new ApiJsonModel($list, 0, 'Success');
    }
}
