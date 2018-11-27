<?php

namespace Rcm\ImmutableHistory;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\Exception\SourceVersionNotFoundException;
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
        EntityManager $entityManger,
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
            VersionActions::CREATE_UNPUBLISHED,
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    /**
     * @param LocatorInterface $locator
     * @param ContentInterface $content
     * @param $userId
     * @param $programmaticReason
     * @param null $resourceIdOverride ONLY provide this if you're resource IDs come from an external system
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function publishFromNothing(
        LocatorInterface $locator,
        ContentInterface $content,
        $userId,
        $programmaticReason,
        $resourceIdOverride = null
    ) {
        $previousPublishedVersion = $this->findPublishedVersionByLocator($locator);

        if ($previousPublishedVersion !== null) {
            //This resource already exists in the history system so use its existing resource id
            $resourceId = $previousPublishedVersion->getResourceId();

            if ($resourceIdOverride !== $resourceId) {
                //Ensure history doesn't get corrupted by unexpected resource-id-to-locator relations
                throw new \RuntimeException(
                    'Cannot override resource ID to a different value than the value found by the locator.'
                    . json_encode(['resourceIdFromLocator' => $resourceId, 'resourceIdOverride' => $resourceIdOverride])
                );
            }
        } elseif ($resourceIdOverride !== null) {
            //Resource ID couldn't be found by locator and an override ID was given so use it
            $resourceId = $resourceIdOverride;
        } else {
            //This resource doesn't exist in the history system yet so make a new resource id for it
            $resourceId = $this->generateResourceId->__invoke();
        }

        $newVersion = new $this->entityClassName(
            $resourceId,
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            VersionActions::PUBLISH,
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
        $previousPublishedVersion = $this->findPublishedVersionByLocator($oldLocator);

        if ($previousPublishedVersion === null) {
            throw new SourceVersionNotFoundException('Locator: ' . json_encode($oldLocator));
        }

        //We must use a transaction or two relcates in the same moment could corrupt the history data chain
        $this->entityManger->getConnection()->beginTransaction();

        $resourceId = $previousPublishedVersion->getResourceId();
        $relocateDepublishVersion = new $this->entityClassName(
            $resourceId,
            new \DateTime(),
            VersionStatuses::DEPUBLISHED,
            VersionActions::RELOCATE_DEPUBLISH,
            $userId,
            $programmaticReason,
            $previousPublishedVersion->getLocator(),
            $previousPublishedVersion->getContentAsArray()
        );
        $this->entityManger->persist($relocateDepublishVersion);
        $this->entityManger->flush($relocateDepublishVersion);

        $relocatePublishVersion = new $this->entityClassName(
            $resourceId,
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            VersionActions::RELOCATE_PUBLISH,
            $userId,
            $programmaticReason,
            $newLocator,
            $previousPublishedVersion->getContentAsArray()
        );

        $this->entityManger->persist($relocatePublishVersion);
        $this->entityManger->flush($relocatePublishVersion);

        $this->entityManger->getConnection()->commit();
    }

    /**
     * @deprecated
     *
     * This is needed because sometimes resources are duplicated-from which don't yet exist in the history
     * system because they are old data. This first trys to do a regular duplicate. If it cannot, it
     * does something akin to "publishFromNothing" but with the "duplicate" action.
     *
     * @param LocatorInterface $locator
     * @param ContentInterface $content
     * @param $userId
     * @param $programmaticReason
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function duplicateBc(
        LocatorInterface $fromLocator,
        LocatorInterface $toLocator,
        ContentInterface $content,
        string $userId,
        string $programmaticReason
    ) {
        try {
            //This will work if the resource already exists in this newer history system
            $this->duplicate(
                $fromLocator,
                $toLocator,
                $userId,
                $programmaticReason . ',duplicateBc::sourceDidExist'
            );
        } catch (SourceVersionNotFoundException $e) {
            //The source resource was not found in the newer history system
            $newVersion = new $this->entityClassName(
                $this->generateResourceId->__invoke(),
                new \DateTime(),
                VersionStatuses::PUBLISHED,
                VersionActions::DUPLICATE,
                $userId,
                $programmaticReason . ',duplicateBc::sourceDidNotExist',
                $toLocator,
                $content
            );

            $this->entityManger->persist($newVersion);
            $this->entityManger->flush($newVersion);
        }
    }

    public function duplicate(
        LocatorInterface $fromLocator,
        LocatorInterface $toLocator,
        string $userId,
        string $programmaticReason
    ) {
        $previousPublishedVersion = $this->findPublishedVersionByLocator($fromLocator);

        if ($previousPublishedVersion === null) {
            throw new SourceVersionNotFoundException('Locator: ' . json_encode($fromLocator));
        }

        $content = $previousPublishedVersion->getContentAsArray();

        $copiedVersion = new $this->entityClassName(
            $this->generateResourceId->__invoke(),
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            VersionActions::DUPLICATE,
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
