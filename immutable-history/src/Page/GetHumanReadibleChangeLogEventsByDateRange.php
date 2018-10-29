<?php

namespace Rcm\ImmutableHistory\Page;

use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\VersionActions;

class GetHumanReadibleChangeLogEventsByDateRange implements GetHumanReadableChangeLogEventsByDateRangeInterface
{
    protected $entityManager;

    public function __construct(EntityManager $entityManger)
    {
        $this->entityManager = $entityManger;
    }

    public function __invoke(\DateTime $greaterThanDate, \DateTime $lessThanDate) : array
    {
        $doctrineRepo = $this->entityManager->getRepository(ImmutablePageVersionEntity::class);

        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->gt('date', $greaterThanDate));
        $criteria->andWhere($criteria->expr()->lt('date', $lessThanDate));

        $entities = $doctrineRepo->matching($criteria)->toArray();

        $entityToHumanReadable = function ($historyItem): ChangeLogEvent {
//            $cmsResource = $historyItem->getCmsResource();

//            $contentVersionId = $historyItem->getContentVersionId();

            switch ($historyItem->getAction()) {
                case VersionActions::CREATE_UNPUBLISHED_FROM_NOTHING:
                    $actionDescription = 'created an unpublished version of';
                    break;
                case VersionActions::PUBLISH_FROM_NORTHING:
                    $actionDescription = 'created a published version of ';
                    break;
                default:
                    throw new \Exception('Unknown action type found: ' . $historyItem->getAction());
            }

            $event = new ChangeLogEvent(); //@TODO clean this up?
            $event->setDate($historyItem->getDate());
            $event->setUserId($historyItem->getUserId());
            $event->setActionDescription($actionDescription);
            $event->setResourceDescription(
                'page "' . $historyItem->getRelativeUrl()
                . '" on site #' . $historyItem->getSiteId()
                . ' ({{siteIdToDomainName}}' . $historyItem->getRelativeUrl() . ')'); //@TODO is this right?
            $event->setMetaData(
                [
                    'siteId' => $historyItem->getSiteId(),
                    'relativeUrl' => $historyItem->getRelativeUrl()
                ]
            );

            return $event;
        };

        return array_map($entityToHumanReadable, $entities);
    }
}
