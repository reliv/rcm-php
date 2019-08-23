<?php

namespace Rcm\Acl;

use RcmUser\User\Entity\UserInterface;

interface GetGroupNamesByUserInterface
{
    /**
     * @param UserInterface|null $user
     * @return string[]
     */
    public function __invoke($user): array;
}
