<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\HumanReadableVersionDescriber;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\User\UserIdToUserFullName;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;

class GetHumanReadableChangeLogEventsByDateRange implements GetHumanReadableChangeLogEventsByDateRangeInterface
{
    protected $entityManager;
    protected $userIdToFullName;
    protected $vesionTypes = [];

    public function __construct(
        EntityManager $entityManger,
        UserIdToUserFullName $userIdToUserFullName
    ) {
        $this->entityManager = $entityManger;
        $this->userIdToFullName = $userIdToUserFullName;
    }

    public function addVersionType(
        string $versionEntityClassName,
        HumanReadableVersionDescriber $humanReadableDescriberService
    ): void {
        $this->vesionTypes[$versionEntityClassName] = $humanReadableDescriberService;
    }

    public function __invoke(\DateTime $greaterThanDate, \DateTime $lessThanDate): array
    {
        $events = [];
        foreach ($this->vesionTypes as $versionEntityClassName => $humanReadableDescriberService) {
            $events = array_merge(
                $events,
                $this->getEventsForSingleVersionType(
                    $greaterThanDate,
                    $lessThanDate,
                    $versionEntityClassName,
                    $humanReadableDescriberService
                )
            );
        }

        return $events;
    }

    protected function getEventsForSingleVersionType(
        \DateTime $greaterThanDate,
        \DateTime $lessThanDate,
        string $versionEntityClassName,
        HumanReadableVersionDescriber $versionDescriber
    ): array {
        $doctrineRepo = $this->entityManager->getRepository($versionEntityClassName);

        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->gt('date', $greaterThanDate));
        $criteria->andWhere($criteria->expr()->lt('date', $lessThanDate));

        $versions = $doctrineRepo->matching($criteria)->toArray();

        foreach ($versions as $version) {
            $this->doDataIntegrityAssertions($version);
        }

        return array_map(
            function (VersionEntityInterface $version) use ($versionDescriber): ChangeLogEvent {
                $event = new ChangeLogEvent();
                $event->date = $version->getDate();
                $event->versionId = $version->getId();
                $event->userId = $version->getUserId();
                $event->userDescription = $this->userIdToFullName->__invoke($version->getUserId());
                $event->resourceTypeDescription = $versionDescriber->getResourceTypeDescription($version);
                $event->actionDescription = VersionActions::DEFAULT_ACTION_DESCRIPTIONS[$version->getAction()];
                $event->resourceLocatorArray = $version->getLocator()->toArray();
                $event->resourceLocationDescription = $versionDescriber->getResourceLocationDescription($version);
                $event->parentCurrentLocationDescription
                    = $versionDescriber->getParentCurrentLocationDescription($version);

                return $event;
            },
            $versions
        );
    }

    /**
     * This will throw an exectpion if it finds anything wrong with the $version entity given to it.
     *
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
