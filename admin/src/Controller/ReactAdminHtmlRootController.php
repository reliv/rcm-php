<?php

namespace RcmAdmin\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Site;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use RcmAdmin\Service\SiteManager;
use RcmUser\Api\Authentication\GetIdentity;
use Zend\Diactoros\Response\JsonResponse;
use \Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use \Zend\Mvc\Controller\AbstractRestfulController;
use RcmUser\Api\Acl\IsAllowed;
use Zend\View\Model\ViewModel;

class ReactAdminHtmlRootController extends AbstractActionController
{
    /**
     * indexAction
     *
     * @return UnauthorizedResponse|ViewModel
     */
    public function indexAction()
    {
        $this->layout()->setTemplate('layout/blank');

        return new ViewModel();
    }
}
