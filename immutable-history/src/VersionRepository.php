<?php

namespace Rcm\ImmutableHistory;

use Rcm\ImmutableHistory\ResourceId\GenerateResourceIdInterface;

class VersionRepository implements VersionRepositoryInterface
{
    protected $entityClassName;
    protected $entityManger;
    protected $generateResourceId;

    /**
     * @TODO make an interface for this repo and make all callers use it
     *
     * VersionRepository constructor.
     * @param $entityClassName
     * @param \Doctrine\ORM\EntityManager $entityManger
     * @param GenerateResourceIdInterface $generateResourceId
     */
    public function __construct(
        $entityClassName,
        \Doctrine\ORM\EntityManager $entityManger,
        GenerateResourceIdInterface $generateResourceId
    ) {
        $this->entityClassName = $entityClassName;
        $this->entityManger = $entityManger;
        $this->generateResourceId = $generateResourceId;
    }

    public function createUnpublishedFromNothing(
        LocatorInterface $locator,
        ContentInterface $content,
        $userId,
        $programmaticReason
    ) {
        $newVersion = new $this->entityClassName(
            $this->generateResourceId->__invoke(),
            new \DateTime(),
            VersionStatuses::UNPUBLISHED,
            VersionActions::CREATE_UNPUBLISHED_FROM_NOTHING,
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function publishFromNothing(
        LocatorInterface $locator,
        ContentInterface $content,
        $userId,
        $programmaticReason
    ) {
        $newVersion = new $this->entityClassName(
            $this->generateResourceId->__invoke(),
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            VersionActions::PUBLISH_FROM_NORTHING,
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function depublish(LocatorInterface $locator, $userId, $programmaticReason)
    {
        $newVersion = new $this->entityClassName(
            $this->generateResourceId->__invoke(),
            new \DateTime(),
            VersionStatuses::DEPUBLISHED,
            VersionActions::DEPUBLISH,
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function relocate(LocatorInterface $oldLocator, LocatorInterface $newLocator)
    {
        $originalEntity = $this->findActiveVersionByLocator($oldLocator);

        //We must use a transaction or two relcates in the same moment could corrupt the history data chain
        $this->entityManger->getConnection()->beginTransaction();

        if ($originalEntity !== null) {
            //This resoruce already exists in the history system so depublish it and use its resource id
            $publishAction = VersionActions::RELOCATE_PUBLISH;
            $resourceId = $originalEntity->getResourceId();
            $relocateDepublishVersion = new $this->entityClassName(
                $this->generateResourceId->__invoke(),
                $resourceId,
                VersionStatuses::DEPUBLISHED,
                VersionActions::RELOCATE_DEPUBLISH,
                $userId,
                $programmaticReason,
                $originalEntity->getLocator(),
                $originalEntity->getContent()
            );
            $this->entityManger->persist($relocateDepublishVersion);
            $this->entityManger->flush($relocateDepublishVersion);
        } else {
            //This resource doesn't exist in the history system yet so make a new resource id for it
            $publishAction = VersionActions::RELOCATE_PUBLISH_FROM_UNKNOWN;
            $resourceId = $this->generateResourceId->__invoke();
        }

        $relocatePublishVersion = new $this->entityClassName(
            $this->generateResourceId->__invoke(),
            $resourceId,
            VersionStatuses::PUBLISHED,
            $publishAction,
            $userId,
            $programmaticReason,
            $newLocator,
            $originalEntity->getContent()
        );

        $this->entityManger->persist($relocatePublishVersion);
        $this->entityManger->flush($relocatePublishVersion);

        $em->getConnection()->commit();
    }

    public function duplicate(LocatorInterface $relocateDepublishVersion, LocatorInterface $relocatePublishVersion)
    {
        $originalEntity = $this->findActiveVersionByLocator($oldLocator);

        //We must use a transaction or two relcates in the same moment could corrupt the history data chain
        $this->entityManger->getConnection()->beginTransaction();

        if ($originalEntity !== null) {
            //This resoruce already exists in the history system so depublish it and use its resource id
            $action = VersionActions::DUPLICATE;
            $resourceId = $originalEntity->getResourceId();
        } else {
            //This resource doesn't exist in the history system yet so make a new resource id for it
            $action = VersionActions::DUPLICATE_FROM_UNKNOWN;
            $resourceId = $this->generateResourceId->__invoke();
        }

        $copiedVersion = new $this->entityClassName(
            $this->generateResourceId->__invoke(),
            $resourceId,
            VersionStatuses::PUBLISHED,
            VersionActions::DUPLICATE,
            $userId,
            $programmaticReason,
            $newLocator,
            $originalEntity->getContent()
        );

        $this->entityManger->persist($copiedVersion);
        $this->entityManger->flush($copiedVersion);

    }

    /**
     * Find the "active" version which means the most recent version that is either
     * "published" or "depublished" while ignoring "unpublished" versions
     *
     * @param LocatorInterface $locator
     * @return VersionEntityInterface | null
     */
    public function findActiveVersionByLocator(LocatorInterface $locator)
    {
        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->in(
            'status',
            [VersionStatuses::PUBLISHED, VersionStatuses::DEPUBLISHED])
        );
        foreach ($locator->toArray() as $column => $value) {
            $criteria->andWhere($criteria->expr()->eq($column, $value));
        }
        $criteria->orderBy(['id' => Criteria::DESC]);
        $criteria->setMaxResults(1);

        $entities = $this->entityManager->getRepository($this->entityClassName)->matching($criteria)->toArray();

        if ($entities[0]) {
            return $entities[0];
        }

        return null;
    }

    public function findUnpublishedVersionsByLocator(LocatorInterface $locator)
    {
        throw new \Exception(
            'findUnpublishedVersionsByLocator() is not implemented yet because it is not currently needed.'
        );

    }

    public function findPublishedVersionsByLocator(LocatorInterface $locator)
    {
        throw new \Exception(
            'findPublishedVersionsByLocator() is not implemented yet because it is not currently needed.'
        );
    }
}
