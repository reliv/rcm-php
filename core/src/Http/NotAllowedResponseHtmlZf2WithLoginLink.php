<?php

namespace Rcm\Http;

use Zend\Http\Request;
use Zend\Http\Response;

/**
 * A ZF2 response that returns 401 with an html message that includes a link to the login page.
 *
 * Class NotAllowedResponseHtmlZf2WithLoginLink
 * @package Rcm\Http
 */
class NotAllowedResponseHtmlZf2WithLoginLink extends NotFoundResponseJsonZf2
{
    public function __construct(Request $request)
    {
        $loginUrl = '/login?redirect=' . urlencode($request->getUri()->getPath());
        $this->setContent('Access denied. Try <a href="' . $loginUrl . '">logging in</a>.');
        $this->setStatusCode(401);
    }
}
