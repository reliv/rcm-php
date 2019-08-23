<?php

namespace Rcm\Http;

use Zend\Http\Response;

/**
 * This looks like a "not found" response to keep with the security practice of
 * "if not allowed, return same thing you would if it doesn't exist".
 */
class NotAllowedResponseJsonZf2 extends NotFoundResponseJsonZf2
{
}
