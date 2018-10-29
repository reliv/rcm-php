<?php
//
//
//namespace Zrcms\CoreApplication\Api\ChangeLog;
//
//use Zrcms\Core\Api\ChangeLog\GetChangeLogByDateRange;
//use Zrcms\Core\Model\ChangeLogEvent;
//
//class GetHumanReadableChangeLogByDateRange
//{
//    protected $getChangeLogByDateRange;
//    protected $changeLogEventToString;
//
//    /**
//     * @param GetChangeLogByDateRange $getChangeLogByDateRange
//     * @param ChangeLogEventToString $changeLogEventToString
//     */
//    public function __construct(
//        GetChangeLogByDateRange $getChangeLogByDateRange,
//        ChangeLogEventToString $changeLogEventToString
//    ) {
//        $this->getChangeLogByDateRange = $getChangeLogByDateRange;
//        $this->changeLogEventToString = $changeLogEventToString;
//    }
//
//    /**
//     * @param \DateTime $greaterThanYear
//     * @param \DateTime $lessThanYear
//     *
//     * @return array
//     * @throws \Exception
//     */
//    public function __invoke(\DateTime $greaterThanYear, \DateTime $lessThanYear)
//    {
//        $humanReadableEvents = [];
//        /** @var ChangeLogEvent $changeLogEvent */
//        foreach ($this->getChangeLogByDateRange->__invoke($greaterThanYear, $lessThanYear) as $changeLogEvent) {
//            $humanReadableEvents[] = [
//                'date' => $changeLogEvent->getDateTime()->format('c'),
//                'description' => $this->changeLogEventToString->__invoke($changeLogEvent)
//            ];
//        }
//
//        return $humanReadableEvents;
//    }
//}
