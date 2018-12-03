<?php

namespace Rcm\ImmutableHistory;

interface VersionRepositoryInterface
{
    public function createUnpublished(
        LocatorInterface $locator,
        ContentInterface $content,
        $userId,
        $programmaticReason
    );

    /**
     * @param LocatorInterface $locator
     * @param ContentInterface $content
     * @param $userId
     * @param $programmaticReason
     * @param null $resourceIdOverride ONLY provide this if you're resource IDs come from an external system
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function publish(
        LocatorInterface $locator,
        ContentInterface $content,
        $userId,
        $programmaticReason,
        $resourceIdOverride = null
    );

    public function depublish(LocatorInterface $locator, string $userId, string $programmaticReason);

    public function relocate(
        LocatorInterface $oldLocator,
        LocatorInterface $newLocator,
        string $userId,
        string $programmaticReason
    );

    /**
     * Backward compatible duplicate
     *
     * @deprecated
     *
     * This is needed because sometimes resources are duplicated-from which don't yet exist in the history
     * system because they are old data. This first trys to do a regular duplicate. If it cannot, it
     * does something akin to "publish" but with the "duplicate" action.
     *
     * @param LocatorInterface $locator
     * @param ContentInterface $content
     * @param string $userId
     * @param string $programmaticReason
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function duplicateBc(
        LocatorInterface $fromLocator,
        LocatorInterface $toLocator,
        ContentInterface $content,
        string $userId,
        string $programmaticReason
    );

    public function duplicate(
        LocatorInterface $fromLocator,
        LocatorInterface $toLocator,
        string $userId,
        string $programmaticReason
    );

    /**
     * Finds the most recent "published" or "depublished" version of a resource
     * and if it is "published" returns it, otherwise returns null.
     *
     * @param LocatorInterface $locator
     * @return VersionEntityInterface | null
     */
    public function findPublishedVersionByLocator(LocatorInterface $locator);

//    public function findUnpublishedVersionsByLocator(LocatorInterface $locator);
//
//    public function findPublishedVersionsByLocator(LocatorInterface $locator);
}
