<?php

namespace RcmUser\Ui\Controller;

use RcmUser\Api\Acl\IsAllowed;
use RcmUser\Api\GetPsrRequest;
use RcmUser\Provider\RcmUserAclResourceProvider;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UserTestController extends AbstractActionController
{
    /**
     * @return array|\Zend\Stdlib\ResponseInterface
     */
    public function indexAction()
    {
        $psrRequest = GetPsrRequest::invoke();

        /** @var IsAllowed $isAllowed */
        $isAllowed = $this->getServiceLocator()->get(
            IsAllowed::class
        );

        $allowed = $isAllowed->__invoke(
            $psrRequest,
            RcmUserAclResourceProvider::RESOURCE_ID_ROOT,
            null
        );

        if (!$allowed) {
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_401);
            $response->setContent(
                $response->renderStatusLine()
            );

            return $response;
        }

        $test = [
            'userController' => $this,
            'doTest' => false,
            'dumpUser' => false,
        ];

        return $test;
    }
}
