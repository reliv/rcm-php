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
     * Finds the most recent active version of a resource and if it is "published" returns it,
     * otherwise returns null.
     *
     * @param LocatorInterface $locator
     * @return VersionEntityInterface | null
     */
    public function findPublishedVersionByLocator(LocatorInterface $locator);

    public function findUnpublishedVersionsByLocator(LocatorInterface $locator);

    public function findPublishedVersionsByLocator(LocatorInterface $locator);
}
