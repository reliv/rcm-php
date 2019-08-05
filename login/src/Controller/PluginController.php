<?php

namespace RcmLogin\Controller;

use Rcm\Plugin\PluginInterface;
use Rcm\Plugin\BaseController;
use RcmLogin\Csrf\CsrfValidator;
use RcmUser\Service\RcmUserService;
use Zend\Authentication\Result;
use Zend\EventManager\Event;
use Zend\Stdlib\ResponseInterface;

class PluginController extends BaseController implements PluginInterface
{
    protected $errorCodeWhiteList = [
        'missing',
        'invalid',
        'systemFailure',
        'unauthorized'
    ];

    protected $csrfValidator;

    public function __construct(
        $config,
        CsrfValidator $csrfValidator,
        $pluginName = null,
        $serviceLocator = null
    ) {
        parent::__construct($config, $pluginName, $serviceLocator);
        $this->csrfValidator = $csrfValidator;
    }

    /**
     * renderInstance
     *
     * @param int $instanceId
     * @param array $instanceConfig
     *
     * @return mixed|\Zend\Http\Response|ResponseInterface|\Zend\View\Model\ViewModel
     */
    public function renderInstance($instanceId, $instanceConfig)
    {
        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig
        );

        $requestData = $this->getFilteredRequestData($this->getRequest());

        $error = null;
        if ($requestData['errorCode']) {
            $error = $instanceConfig['translate'][$requestData['errorCode']];
        }

        $view->setVariables(
            [
                'error' => $error,
                'username' => $requestData['username'],
                'redirect' => $requestData['redirect'],
                'csrf' => $this->csrfValidator->getHash()
            ]
        );

        return $view;
    }

    protected function getFilteredRequestData($request)
    {
        $errorCode = '';

        //Ensure error code input is whitelisted to improve security.
        if (in_array($this->getRequest()->getQuery('errorCode'), $this->errorCodeWhiteList)) {
            $errorCode = $this->getRequest()->getQuery('errorCode');
        }

        return [
            'errorCode' => $errorCode,
            'username' => filter_var($this->getRequest()->getQuery('username'), FILTER_SANITIZE_STRIPPED),
            'redirect' => filter_var($this->getRequest()->getQuery('redirect'))
        ];
    }
}
