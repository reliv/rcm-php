<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

class GetAllSortedChangeLogEventsByDateRange
{
    protected $children = [];

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
        $allEvents = [];
        foreach ($this->children as $child) {
            $events = $child->__invoke($greaterThanDate, $lessThanDate);
            /** @var ChangeLogEvent $event */
            foreach ($events as $event) {
                $allEvents[] = $event;
            }
        }

        //Sort by "date desc", then by "versionId desc" incase events happened in the same second
        usort($allEvents, function ($a, $b) {
            if ($a->getDate() == $b->getDate()) { //Do not change this to "==="
                return $a->getVersionId() < $b->getVersionId();
            } else {
                return $a->getDate() < $b->getDate();
            }
        });

        return $allEvents;
    }
}
