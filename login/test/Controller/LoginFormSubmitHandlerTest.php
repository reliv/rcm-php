<?php


namespace RcmLogin\Test\RcmLogin\Controller;


use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use RcmLogin\Controller\LoginFormSubmitHandler;
use RcmLogin\Csrf\CsrfValidator;
use RcmUser\Service\RcmUserService;
use Zend\Diactoros\ServerRequest;
use Zend\EventManager\EventManager;
use Zend\Validator\Csrf;
use \Mockery;

class LoginFormSubmitHandlerTest extends TestCase
{
    public function testReturnsRedirectResponseWhenCsrfInvalid()
    {
        $invalidCSrfValue = 'asdfhas787dfhas8d76fhas8f';

        $csrfValidator = Mockery::mock(CsrfValidator::class);
        $csrfValidator->allows()->isValid($invalidCSrfValue)->andReturns(false);
        $unit = new LoginFormSubmitHandler(
            Mockery::mock(RcmUserService::class),
            Mockery::mock(EventManager::class),
            $csrfValidator
        );

        $request = (new ServerRequest())->withParsedBody([
            'redirect' => '',
            'csrf' => $invalidCSrfValue,
            'username' => 'testusername',
            'password' => 'testpassword'
        ]);

        $response = $unit->process($request, Mockery::mock(DelegateInterface::class));

        $this->assertEquals(302, $response->getStatusCode());
    }
}
