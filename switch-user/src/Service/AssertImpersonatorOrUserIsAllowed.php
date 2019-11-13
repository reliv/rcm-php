<?php

namespace Rcm\SwitchUser\Service;

use Psr\Container\ContainerInterface;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\Exception\NotAllowedByBusinessLogicException;
use Rcm\Acl\Exception\NotAllowedByQueryRunException;
use Rcm\Acl\IsAllowedByUser;
use Rcm\Acl\NotAllowedException;

class AssertImpersonatorOrUserIsAllowed
{
    protected $assertIsAllowed;
    protected $assertImpersonatorIsAllowed;

    public function __construct(
        ContainerInterface $requestContext,
        AssertImpersonatorIsAllowed $assertImpersonatorIsAllowed
    ) {
        $this->assertIsAllowed = $requestContext->get(AssertIsAllowed::class);
        $this->assertImpersonatorIsAllowed = $assertImpersonatorIsAllowed;
    }

    /**
     * Throws an exception if both the current user and the impersonator don't have access.
     * @param string $action
     * @param array $properties
     * @throws \Exception
     */
    public function __invoke(string $action, array $properties)
    {
        try {
            $this->assertIsAllowed->__invoke($action, $properties);
        } catch (NotAllowedException $exception) {
            try {
                $this->assertImpersonatorIsAllowed->__invoke($action, $properties);
            } catch (NotAllowedException $exception) {
                throw new NotAllowedByBusinessLogicException(
                    'Neither impersonator nor current user is allowed'
                );
            }
        }
    }
}
