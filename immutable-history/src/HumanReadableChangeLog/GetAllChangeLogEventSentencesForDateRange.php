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
                    'date' => $event->getDate()->format('c'),
                    'description' => $this->changeLogEventToSentence->__invoke($event)
                ];
            }
        }

        return $humanReadableEvents;
    }
}
