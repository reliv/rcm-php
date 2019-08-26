<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\Http\Response;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\PageTypeSecureRepo;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\View\Model\JsonModel;

class ApiAdminPageTypesController extends ApiAdminBaseController
{

    /**
     * getList of available page types
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        $secureRepo = $this->getServiceLocator()->get(RequestContext::class)
            ->get(PageTypeSecureRepo::class);

        try {
            $list = $secureRepo->findAll();
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        return new ApiJsonModel($list, 0, 'Success');
    }
}
