<?php

namespace Rcm\ImmutableHistory;

use Doctrine\Common\Collections\Criteria;
use Rcm\ImmutableHistory\ResourceId\GenerateResourceIdInterface;

class VersionRepository implements VersionRepositoryInterface
{
    protected $entityClassName;
    protected $entityManger;
    protected $generateResourceId;

    /**
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
        $previousPublishedVersion = $this->findPublishedVersionByLocator($locator);

        if ($previousPublishedVersion !== null) {
            //This resource already exists in the history system so use its existing resource id
            $resourceId = $previousPublishedVersion->getResourceId();
        } else {
            //This resource doesn't exist in the history system yet so make a new resource id for it
            $resourceId = $this->generateResourceId->__invoke();
        }

        $newVersion = new $this->entityClassName(
            $resourceId,
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
        $previousPublishedVersion = $this->findPublishedVersionByLocator($locator);

        if ($previousPublishedVersion !== null) {
            //This resource already exists in the history system so use its existing resource id
            $resourceId = $previousPublishedVersion->getResourceId();
        } else {
            //This resource doesn't exist in the history system yet so make a new resource id for it
            $resourceId = $this->generateResourceId->__invoke();
        }

        $newVersion = new $this->entityClassName(
            $resourceId,
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

    public function depublish(LocatorInterface $locator, string $userId, string $programmaticReason)
    {
        $previousPublishedVersion = $this->findPublishedVersionByLocator($locator);

        if ($previousPublishedVersion !== null) {
            //This resource already exists in the history system so use its existing resource id
            $resourceId = $previousPublishedVersion->getResourceId();
        } else {
            //This resource doesn't yet exist in the history system yet so make a new resource id for it
            $resourceId = $this->generateResourceId->__invoke();
        }

        $newVersion = new $this->entityClassName(
            $resourceId,
            new \DateTime(),
            VersionStatuses::DEPUBLISHED,
            VersionActions::DEPUBLISH,
            $userId,
            $programmaticReason,
            $locator
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function relocate(
        LocatorInterface $oldLocator,
        LocatorInterface $newLocator,
        string $userId,
        string $programmaticReason
    ) {
        throw new \Exception('relocate() was never fully tested and is disabled right now.');
//        $previousPublishedVersion = $this->findPublishedVersionByLocator($oldLocator);
//
//        //We must use a transaction or two relcates in the same moment could corrupt the history data chain
//        $this->entityManger->getConnection()->beginTransaction();
//
//        if ($previousPublishedVersion !== null) {
//            //This resource already exists in the history system so depublish it and use its resource id
//            $publishAction = VersionActions::RELOCATE_PUBLISH;
//            $resourceId = $previousPublishedVersion->getResourceId();
//            $relocateDepublishVersion = new $this->entityClassName(
//                $this->generateResourceId->__invoke(),
//                $resourceId,
//                VersionStatuses::DEPUBLISHED,
//                VersionActions::RELOCATE_DEPUBLISH,
//                $userId,
//                $programmaticReason,
//                $previousPublishedVersion->getLocator(),
//                $previousPublishedVersion->getContent()
//            );
//            $this->entityManger->persist($relocateDepublishVersion);
//            $this->entityManger->flush($relocateDepublishVersion);
//        } else {
//            //This resource doesn't exist in the history system yet so make a new resource id for it
//            $publishAction = VersionActions::RELOCATE_PUBLISH_FROM_UNKNOWN;
//            $resourceId = $this->generateResourceId->__invoke();
//        }
//
//        $relocatePublishVersion = new $this->entityClassName(
//            $this->generateResourceId->__invoke(),
//            new \DateTime(),
//            VersionStatuses::PUBLISHED,
//            $publishAction,
//            $userId,
//            $programmaticReason,
//            $newLocator,
//            $previousPublishedVersion->getContent()
//        );
//
//        $this->entityManger->persist($relocatePublishVersion);
//        $this->entityManger->flush($relocatePublishVersion);
//
//        $em->getConnection()->commit();
    }

    public function duplicate(
        LocatorInterface $fromLocator,
        LocatorInterface $toLocator,
        string $userId,
        string $programmaticReason
    ) {
        $previousPublishedVersion = $this->findPublishedVersionByLocator($fromLocator);

        if ($previousPublishedVersion !== null) {
            //The "from" resource already exists in the history system
            $action = VersionActions::DUPLICATE;
            $content = $previousPublishedVersion->getContentAsArray();
        } else {
            //This "from" resource does NOT already exist in the history system.
            $action = VersionActions::DUPLICATE_FROM_UNKNOWN;
            $content = null; //@TODO is this ok? Probably not good!
        }

        $copiedVersion = new $this->entityClassName(
            $this->generateResourceId->__invoke(),
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            $action,
            $userId,
            $programmaticReason,
            $toLocator,
            $content
        );

        $this->entityManger->persist($copiedVersion);
        $this->entityManger->flush($copiedVersion);
    }

    /**
     * Finds the most recent "published" or "depublished" version of a resource
     * and if it is "published" returns it, otherwise returns null.
     *
     * @param LocatorInterface $locator
     * @return VersionEntityInterface | null
     */
    public function findPublishedVersionByLocator(LocatorInterface $locator)
    {
        $entity = $this->findActiveVersionByLocator($locator);

        if ($entity !== null && $entity->getStatus() === VersionStatuses::PUBLISHED) {
            return $entity;
        }

        return null;
    }

//    public function findUnpublishedVersionsByLocator(LocatorInterface $locator)
//    {
//        throw new \Exception(
//            'findUnpublishedVersionsByLocator() is not implemented yet because it is not currently needed.'
//        );
//
//    }
//
//    public function findPublishedVersionsByLocator(LocatorInterface $locator)
//    {
//        throw new \Exception(
//            'findPublishedVersionsByLocator() is not implemented yet because it is not currently needed.'
//        );
//    }

    /**
     * Find the "active" version which means the most recent version that is either
     * "published" or "depublished" while ignoring "unpublished" versions
     *
     * @param LocatorInterface $locator
     * @return VersionEntityInterface | null
     */
    protected function findActiveVersionByLocator(LocatorInterface $locator)
    {
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->in(
            'status',
            [VersionStatuses::PUBLISHED, VersionStatuses::DEPUBLISHED])
        );
        foreach ($locator->toArray() as $column => $value) {
            $criteria->andWhere($criteria->expr()->eq($column, $value));
        }
        $criteria->orderBy(['id' => Criteria::DESC]);
        $criteria->setMaxResults(1);

        $entities = $this->entityManger->getRepository($this->entityClassName)->matching($criteria)->toArray();

        if (isset($entities[0])) {
            return $entities[0];
        }

        return null;
    }
}
