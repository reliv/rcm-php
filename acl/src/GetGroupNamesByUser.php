<?php

namespace Rcm\Acl;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\Entity\Group;
use Rcm\Acl\Entity\UserGroup;
use Rcm\Acl\HardCodedGroups;
use RcmUser\User\Entity\UserInterface;

class GetGroupNamesByUser implements GetGroupNamesByUserInterface
{
    protected $entityManager;

    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UserInterface|null $user
     * @return string[]
     */
    public function __invoke($user): array
    {
        if ($user === null) {
            return [HardCodedGroups::EVERYONE, HardCodedGroups::GUEST];
        }

        $userGroupsEntities = $this->entityManager->getRepository(UserGroup::class)
            ->findBy(['userId' => $user->getId()]);

        $groupNames = array_map(
            function (UserGroup $userGroupRelation) {
                return $userGroupRelation->getGroup();
            },
            $userGroupsEntities
        );
        $groupNames[] = HardCodedGroups::EVERYONE;
        return $groupNames;
    }
}
