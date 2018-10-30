<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

use Rcm\ImmutableHistory\User\UserIdToUserFullName;

class ChangeLogEventToSentence
{
    protected $userIdToUserFullName;

    public function __construct(UserIdToUserFullName $userIdToUserFullName)
    {
        $this->userIdToUserFullName = $userIdToUserFullName;
    }

    /**
     * @param ChangeLogEvent $changeLogEvent
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        ChangeLogEvent $changeLogEvent
    ): string {
        return 'User #' . $changeLogEvent->getUserId()
            . (' (' . $this->userIdToUserFullName->__invoke($changeLogEvent->getUserId()) . ')')
            . ' ' . $changeLogEvent->getActionDescription()
            . ' ' . $changeLogEvent->getResourceDescription();
    }
}
