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

    //@TODO have interface for children?
    public function addChild(
        $child
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

        //@TODO re-sort these by date since these are comming from multipule sources
        //@TODO preserve order within same second?
        return $humanReadableEvents;
    }
}
