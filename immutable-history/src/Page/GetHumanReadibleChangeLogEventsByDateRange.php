<?php

namespace Rcm\ImmutableHistory\Page;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEvent;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRangeInterface;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\User\UserIdToUserFullName;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;

class GetHumanReadibleChangeLogEventsByDateRange implements GetHumanReadableChangeLogEventsByDateRangeInterface
{
    protected $entityManager;

    protected $siteIdToDomainName;
    protected $userIdToFullName;

    public function __construct(
        EntityManager $entityManger,
        SiteIdToDomainName $siteIdToDomainName,
        UserIdToUserFullName $userIdToUserFullName
    ) {
        $this->entityManager = $entityManger;
        $this->siteIdToDomainName = $siteIdToDomainName;
        $this->userIdToFullName = $userIdToUserFullName;
    }

    public function __invoke(\DateTime $greaterThanDate, \DateTime $lessThanDate): array
    {
        $doctrineRepo = $this->entityManager->getRepository(ImmutablePageVersionEntity::class);

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
                $event->resourceTypeDescription = 'page';
                $event->actionDescription = VersionActions::DEFAULT_ACTION_DESCRIPTIONS[$version->getAction()];
                $event->resourceLocatorArray = $version->getLocatorAsArray();
                $event->resourceLocationDescription = $version->getPathname();
                $event->parentCurrentLocationDescription = $this->siteIdToDomainName->__invoke($version->getSiteId());

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
