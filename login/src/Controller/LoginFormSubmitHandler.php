<?php

namespace RcmLogin\Controller;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RcmLogin\Csrf\CsrfValidator;
use RcmUser\Service\RcmUserService;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\EventManager\Event;
use Zend\EventManager\EventManager;
use Zend\Authentication\Result;

class LoginFormSubmitHandler implements MiddlewareInterface
{
    protected $rcmUserService;

    protected $eventManager;

    protected $loginFormUrl;

    protected $afterLoginSuccessUrl;

    protected $disabledAccountUrl;

    protected $redirectWhitelistRegex;

    protected $csrfValidator;

    /**
     * LoginFormSubmitHandler constructor.
     * @param RcmUserService $rcmUserService
     * @param EventManager $eventManager
     * @param string $loginFormUrl
     * @param string $afterLoginSuccessUrl
     * @param string $disabledAccountUrl
     * @param string $redirectWhitelistRegex By default this allows only relative URLS to prevent malicous redirects
     */
    public function __construct(
        RcmUserService $rcmUserService,
        EventManager $eventManager,
        CsrfValidator $csrfValidator,
        $loginFormUrl = '/login',
        $afterLoginSuccessUrl = '/login-home',
        $disabledAccountUrl = '/account-issue',
        $redirectWhitelistRegex = '/^\/((?!\/)).*$/'
    ) {
        $this->rcmUserService = $rcmUserService;
        $this->eventManager = $eventManager;
        $this->csrfValidator = $csrfValidator;
        $this->loginFormUrl = $loginFormUrl;
        $this->afterLoginSuccessUrl = $afterLoginSuccessUrl;
        $this->disabledAccountUrl = $disabledAccountUrl;
        $this->redirectWhitelistRegex = $redirectWhitelistRegex;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $requestBody = $request->getParsedBody();

        if (!array_key_exists('redirect', $requestBody)
            || !array_key_exists('username', $requestBody)
            || !array_key_exists('password', $requestBody)
            || !array_key_exists('csrf', $requestBody)
        ) {
            return new RedirectResponse(
                $this->loginFormUrl . '?errorCode=systemFailure&detailedErrorCode=requestMissingBodyField'
                . '&redirect=' . $redirectParam
            );
        }

        $username = trim(filter_var($requestBody['username'], FILTER_SANITIZE_STRING));
        $password = filter_var($requestBody['password'], FILTER_SANITIZE_STRING);
        $redirectParam = filter_var($requestBody['redirect']);

        if (!$this->csrfValidator->isValid($requestBody['csrf'])) {
            return new RedirectResponse(
                $this->loginFormUrl . '?errorCode=systemFailure&detailedErrorCode=invalidCsrfToken'
                . '&username=' . urlencode($username)
                . '&redirect=' . $redirectParam
            );
        }

        if (empty($username) || empty($password)) {
            return new RedirectResponse(
                $this->loginFormUrl . '?errorCode=missing'
                . '&username=' . urlencode($username)
                . '&redirect=' . $redirectParam
            );
        }

        $user = $this->rcmUserService->buildNewUser();
        $user->setUsername($username);
        $user->setPassword($password);

        /** @var \Zend\Authentication\Result $authResult */
        $authResult = $this->rcmUserService->authenticate($user);

        // Valid auth
        if (!$authResult->isValid()) {
            /**
             * Used for times when we want to tell them their username and password were good but there account has been
             * disabled for some other reasion.
             */
            if ($authResult->getCode() == Result::FAILURE_UNCATEGORIZED
                && !empty($this->disabledAccountUrl)
            ) {
                return new RedirectResponse($this->disabledAccountUrl);
            }

            return new RedirectResponse($this->loginFormUrl . '?errorCode=invalid'
                . '&username=' . urlencode($username)
                . '&redirect=' . $redirectParam);
        }

        $event = new Event('LoginSuccessEvent', $this);

        /** @var \Zend\EventManager\ResponseCollection $responses */
        $responses = $this->eventManager->trigger($event, null, [], function ($v) {
            return ($v instanceof ResponseInterface);
        });

        $response = $responses->last();

        if ($response instanceof ResponseInterface) {
            return $response;
        }

        if ($redirectParam && preg_match($this->redirectWhitelistRegex, $redirectParam)) {
            //If we have been requested to redirect the user to somewhere besides the default place, do that
            return new RedirectResponse($redirectParam);
        }

        return new RedirectResponse($this->afterLoginSuccessUrl);
    }
}
