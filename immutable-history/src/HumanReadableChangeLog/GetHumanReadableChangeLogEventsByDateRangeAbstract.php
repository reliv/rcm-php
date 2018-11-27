<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\User\UserIdToUserFullName;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;

abstract class GetHumanReadableChangeLogEventsByDateRangeAbstract implements GetHumanReadableChangeLogEventsByDateRangeInterface
{
    protected $entityManager;
    protected $userIdToFullName;
    protected $versionEntityClassName;

    public function __construct(
        EntityManager $entityManger,
        UserIdToUserFullName $userIdToUserFullName,
        string $versionEntityClassName
    ) {
        $this->entityManager = $entityManger;
        $this->userIdToFullName = $userIdToUserFullName;
        $this->versionEntityClassName = $versionEntityClassName;
    }

    public function __invoke(\DateTime $greaterThanDate, \DateTime $lessThanDate): array
    {
        $doctrineRepo = $this->entityManager->getRepository($this->versionEntityClassName);

        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->gt('date', $greaterThanDate));
        $criteria->andWhere($criteria->expr()->lt('date', $lessThanDate));

        $versions = $doctrineRepo->matching($criteria)->toArray();

        foreach ($versions as $version) {
            $this->doDataIntegrityAssertions($version);
        }

        return array_map(
            function (VersionEntityInterface $version): ChangeLogEvent {
                $event = new ChangeLogEvent();
                $event->date = $version->getDate();
                $event->versionId = $version->getId();
                $event->userId = $version->getUserId();
                $event->userDescription = $this->userIdToFullName->__invoke($version->getUserId());
                $event->resourceTypeDescription = $this->getResourceTypeDescription($version);
                $event->actionDescription = VersionActions::DEFAULT_ACTION_DESCRIPTIONS[$version->getAction()];
                $event->resourceLocatorArray = $version->getLocator()->toArray();
                $event->resourceLocationDescription = $this->getResourceLocationDescription($version);
                $event->parentCurrentLocationDescription = $this->getParentCurrentLocationDescription($version);

                return $event;
            },
            $versions
        );
    }

    abstract protected function getResourceTypeDescription(VersionEntityInterface $version);

    abstract protected function getParentCurrentLocationDescription(VersionEntityInterface $version);

    abstract protected function getResourceLocationDescription(VersionEntityInterface $version);

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
