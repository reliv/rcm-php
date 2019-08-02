<?php

namespace RcmUser\Api;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetPsrRequest
{
    /**
     * @return \Zend\Diactoros\ServerRequest
     */
    public static function invoke()
    {
        return ServerRequestFactory::fromGlobals();
    }

    /**
     * @return ServerRequestInterface
     */
    public function __invoke()
    {
        return self::invoke();
    }
}
