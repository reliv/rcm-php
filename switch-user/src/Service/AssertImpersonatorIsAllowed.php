<?php

namespace Rcm\SwitchUser\Service;

use Rcm\Acl\Exception\NotAllowedByBusinessLogicException;
use Rcm\Acl\Exception\NotAllowedByQueryRunException;
use Rcm\Acl\IsAllowedByUser;

class AssertImpersonatorIsAllowed
{
    protected $isAllowedByUser;
    protected $switchUserService;

    public function __construct(
        IsAllowedByUser $isAllowedByUser,
        SwitchUserService $switchUserService
    ) {
        $this->isAllowedByUser = $isAllowedByUser;
        $this->switchUserService = $switchUserService;
    }

    /**
     * Throws an exception if the CURRENT user/request's IMPERSONATOR does NOT have access to the
     * given action and security properties.
     *
     * @param string $action
     * @param array $properties
     * @throws \Exception
     */
    public function __invoke(string $action, array $properties)
    {
        $user = $this->switchUserService->getCurrentImpersonatorUser();

        if (!$user) {
            throw new NotAllowedByBusinessLogicException('No impersonator currently exists');
        }

        if (!$this->isAllowedByUser->__invoke($action, $properties, $user)) {
            throw new NotAllowedByQueryRunException(
                'An ACL query ran and found the following to be not allowed for current impersonator: '
                . json_encode(
                    [
                        'action' => $action,
                        'properties' => $properties
                    ]
                )
            );
        }
    }
}
