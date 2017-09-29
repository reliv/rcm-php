<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\HasIdentity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsUserLoggedInBasic implements IsUserLoggedIn
{
    protected $hasIdentity;

    /**
     * @param HasIdentity $hasIdentity
     */
    public function __construct(
        HasIdentity $hasIdentity
    ) {
        $this->hasIdentity = $hasIdentity;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request
    ):bool {
        return $this->hasIdentity->__invoke(
            $request
        );
    }
}
