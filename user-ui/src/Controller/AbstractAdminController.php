<?php

namespace RcmUser\Ui\Controller;

use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\GetPsrRequest;
use RcmUser\Provider\RcmUserAclResourceProvider;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AbstractAdminController extends AbstractActionController
{
    /**
     * isAllowed
     *
     * @param string $resourceId resourceId
     * @param string $privilege  privilege
     *
     * @return mixed
     */
    public function isAllowed(
        $resourceId = RcmUserAclResourceProvider::RESOURCE_ID_ROOT,
        $privilege = null
    ) {
        $psrRequest = GetPsrRequest::invoke();

        /** @var IsAllowed $isAllowed */
        $isAllowed = $this->getServiceLocator()->get(
            IsAllowed::class
        );

        return $isAllowed->__invoke(
            $psrRequest,
            $resourceId,
            $privilege
        );
    }

    /**
     * getNotAllowedResponse
     *
     * @return mixed
     */
    public function getNotAllowedResponse()
    {
        $response = $this->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_401);
        $response->setContent($response->renderStatusLine());

        return $response;
    }

    /**
     * buildView
     *
     * @param array $viewArr viewArr
     *
     * @return ViewModel
     */
    protected function buildView($viewArr = [])
    {
        $view = new ViewModel($viewArr);

        return $view;
    }
}
