<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

class ChangeLogEventToSentence
{
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
            . (' ({{userIdToFullName}})')
            . ' ' . $changeLogEvent->getActionDescription()
            . ' ' . $changeLogEvent->getResourceDescription();
    }
}
