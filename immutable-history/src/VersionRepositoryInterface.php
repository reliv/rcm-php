<?php

namespace Rcm\ImmutableHistory;

interface VersionRepositoryInterface
{
    public function createUnpublishedFromNothing(
        LocatorInterface $locator,
        ContentInterface $content,
        $userId,
        $programmaticReason
    );

    public function publishFromNothing(
        LocatorInterface $locator,
        ContentInterface $content,
        $userId,
        $programmaticReason
    );

    public function depublish(LocatorInterface $locator, string $userId, string $programmaticReason);

    public function relocate(LocatorInterface $oldLocator, LocatorInterface $newLocator, string $userId, string $programmaticReason);

    public function duplicate(LocatorInterface $fromLocator, LocatorInterface $toLocator, string $userId, string $programmaticReason);

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
