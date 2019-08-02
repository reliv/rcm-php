<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Service\UserPropertyService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserPropertyBasic implements GetUserProperty
{
    protected $userPropertyService;

    /**
     * @param UserPropertyService $userPropertyService
     */
    public function __construct(
        UserPropertyService $userPropertyService
    ) {
        $this->userPropertyService = $userPropertyService;
    }

    /**
     * @param UserInterface $user
     * @param string        $propertyNameSpace
     * @param null          $default
     * @param bool          $refresh
     *
     * @return mixed
     */
    public function __invoke(
        UserInterface $user,
        $propertyNameSpace,
        $default = null,
        $refresh = false
    ) {
        return $this->userPropertyService->getUserProperty(
            $user,
            $propertyNameSpace,
            $default,
            $refresh
        );
    }
}
