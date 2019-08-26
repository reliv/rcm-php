<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\ThemeSecureRepo;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

class ApiAdminThemeController extends ApiAdminBaseController
{
    /**
     * getList of available page types
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        $secureRepo = $this->getServiceLocator()->get(RequestContext::class)
            ->get(ThemeSecureRepo::class);

        try {
            $list = $secureRepo->findAll();
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        return new ApiJsonModel($list, 0, 'Success');
    }
}
