<?php

namespace Rcm\Acl\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\Entity\Group;
use Rcm\Acl\Entity\UserGroup;
use Rcm\Acl\HardCodedGroups;

class GetGroupIdsByUserId
{
    protected $entityManager;

    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string|null $userId
     * @return int[]
     */
    public function __invoke($userId): array
    {
        if ($userId === null) {
            //If there is no user, use the hard coded guest groups
            return HardCodedGroups::GUEST_GROUPS;
        }

        $groupIdResults = $this->entityManager->createQueryBuilder()
            ->select('IDENTITY(userGroup.group)')
            ->from(UserGroup::class, 'userGroup')
            ->Where('userGroup.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();

        $groupIds = [];
        foreach ($groupIdResults as $groupIdResult) {
            foreach ($groupIdResult as $groupId) {
                $groupIds[] = (int)$groupId;
            }
        }

        return $groupIds;
    }
}
