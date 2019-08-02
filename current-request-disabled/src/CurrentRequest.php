<?php

namespace Rcm\CurrentRequest;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequest;

/**
 * @deprecated user "current request context" instead.
 *
 * This is place-holder to allow the Rcm\CurrentRequest\CurrentRequest
 * service to auto-complete.
 *
 * Class CurrentRequest
 * @package Rcm\CurrentRequest
 */
class CurrentRequest extends ServerRequest implements ServerRequestInterface
{

}
