<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\Http\Response;
use Rcm\RequestContext\RequestContext;
use Rcm\SecureRepo\LanguageSecureRepo;
use Rcm\View\Model\ApiJsonModel;
use RcmUser\Service\RcmUserService;
use Zend\View\Model\JsonModel;

class ApiAdminLanguageController extends ApiAdminBaseController
{
    /**
     * getList
     *
     * @return mixed|JsonModel
     */
    public function getList()
    {
        $secureRepo = $this->getServiceLocator()->get(RequestContext::class)
            ->get(LanguageSecureRepo::class);

        try {
            $list = $secureRepo->findAll(['languageName' => 'ASC']);
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        return new ApiJsonModel($list, 0, 'Success');
    }
}
