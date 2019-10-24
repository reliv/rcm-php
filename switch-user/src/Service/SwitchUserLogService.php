<?php

namespace Rcm\SwitchUser\Service;

use Doctrine\ORM\EntityManager;
use Rcm\SwitchUser\Entity\LogEntry;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SwitchUserLogService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * logAction
     *
     * @param string $adminUserId
     * @param string $targetUserId
     * @param string $action
     * @param bool   $actionSuccess
     *
     * @return void
     */
    public function logAction($adminUserId, $targetUserId, $action, $actionSuccess)
    {
        $entry = new LogEntry($adminUserId, $targetUserId, $action, $actionSuccess);

        $this->entityManager->persist($entry);
        $this->entityManager->flush($entry);
    }
}
