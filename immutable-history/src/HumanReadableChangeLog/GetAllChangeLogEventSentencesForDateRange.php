<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

class GetAllChangeLogEventSentencesForDateRange
{
    protected $children = [];
    protected $changeLogEventToSentence;

    /**
     * @param ChangeLogEventToSentence $changeLogEventToSentence
     */
    public function __construct(
        ChangeLogEventToSentence $changeLogEventToSentence
    ) {
        $this->changeLogEventToSentence = $changeLogEventToSentence;
    }

    public function addChild(
        GetHumanReadableChangeLogEventsByDateRangeInterface $child
    ) {
        $this->children[] = $child;
    }

    /**
     * @param \DateTime $greaterThanDate
     * @param \DateTime $lessThanDate
     *
     * @return array
     * @throws \Exception
     */
    public function __invoke(\DateTime $greaterThanDate, \DateTime $lessThanDate)
    {
        $humanReadableEvents = [];
        foreach ($this->children as $child) {
            $events = $child->__invoke($greaterThanDate, $lessThanDate);
            /** @var ChangeLogEvent $event */
            foreach ($events as $event) {
                $humanReadableEvents[] = [
                    'versionId' => $event->getVersionId(),
                    'date' => $event->getDate()->format('c'),
                    'description' => $this->changeLogEventToSentence->__invoke($event)
                ];
            }
        }

        //Sort by "date desc", then by "versionId desc" incase events happened in the same second
        usort($humanReadableEvents, function ($a, $b) {
            if ($a['date'] === $b['date']) {
                return $a['versionId'] < $b['versionId'];
            } else {
                return $a['date'] < $b['date'];
            }
        });

        return $humanReadableEvents;
    }
}
