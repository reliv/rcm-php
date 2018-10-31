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

    public function depublish(LocatorInterface $locator, $userId, $programmaticReason);

    public function relocate(LocatorInterface $oldLocator, LocatorInterface $newLocator);

    public function duplicate(LocatorInterface $relocateDepublishVersion, LocatorInterface $relocatePublishVersion);

    /**
     * Find the "active" version which means the most recent version that is either
     * "published" or "depublished" while ignoring "unpublished" versions
     *
     * @param LocatorInterface $locator
     * @return VersionEntityInterface | null
     */
    public function findActiveVersionByLocator(LocatorInterface $locator);

    public function findUnpublishedVersionsByLocator(LocatorInterface $locator);

    public function findPublishedVersionsByLocator(LocatorInterface $locator);
}
