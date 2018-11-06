<?php

namespace Rcm\ImmutableHistory\Page;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\Site\UserIdToUserFullName;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;

class GetHumanReadibleChangeLogEventsByDateRange implements GetHumanReadableChangeLogEventsByDateRangeInterface
{
    protected $entityManager;

    protected $siteIdToDomainName;

    public function __construct(
        EntityManager $entityManger,
        SiteIdToDomainName $siteIdToDomainName
    ) {
        $this->entityManager = $entityManger;
        $this->siteIdToDomainName = $siteIdToDomainName;
    }

    public function __invoke(\DateTime $greaterThanDate, \DateTime $lessThanDate): array
    {
        $doctrineRepo = $this->entityManager->getRepository(ImmutablePageVersionEntity::class);

        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->gt('date', $greaterThanDate));
        $criteria->andWhere($criteria->expr()->lt('date', $lessThanDate));
        $criteria->orderBy(['date' => Criteria::DESC, 'id' => Criteria::DESC]);

        $versions = $doctrineRepo->matching($criteria)->toArray();

        foreach ($versions as $version) {
            $this->doDataIntegrityAssertions($version);
        }

        $entityToHumanReadable = function ($version): ChangeLogEvent {
            switch ($version->getAction()) {
                case VersionActions::CREATE_UNPUBLISHED_FROM_NOTHING:
                    $actionDescription = 'created a draft of';
                    break;
                case VersionActions::PUBLISH_FROM_NORTHING:
                    $actionDescription = 'published to';
                    break;
                case VersionActions::DEPUBLISH:
                    $actionDescription = 'depublished';
                    break;
                case VersionActions::DUPLICATE:
                    $actionDescription = 'as part of a copy operation, published to ';
                    break;
                case VersionActions::RELOCATE_DEPUBLISH:
                    $actionDescription = 'as part of a move operation, depublished';
                    break;
                case VersionActions::RELOCATE_PUBLISH:
                    $actionDescription = 'as part of a move operation, published to';
                    break;
                default:
                    throw new \Exception('Unknown action type found: ' . $version->getAction());
            }

            $event = new ChangeLogEvent();
            $event->setDate($version->getDate());
            $event->setUserId($version->getUserId());
            $event->setActionDescription($actionDescription);
            $event->setResourceDescription(
                ' page "' . $version->getPathname()
                . '" on site #' . $version->getSiteId()
                . ' (' . $this->siteIdToDomainName->__invoke($version->getSiteId()) . $version->getPathname() . ')');
            $event->setMetaData(
                [
                    'siteId' => $version->getSiteId(),
                    'relativeUrl' => $version->getPathname()
                ]
            );

            return $event;
        };

        return array_map($entityToHumanReadable, $versions);
    }

    /**
     * Note: this could be moved to a shared location later on
     *
     * @param VersionEntityInterface $version
     * @throws \Exception
     */
    protected function doDataIntegrityAssertions(VersionEntityInterface $version)
    {
        $content = $version->getContentAsArray();
        $action = $version->getAction();

        if (
            ($content == null || (is_array($content) && count($content) === 0))
            && $action !== VersionActions::DEPUBLISH
        ) {
            throw new \Exception('Found missing content for an action type that should have content');
        }
    }
}
