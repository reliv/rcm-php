<?php

namespace Rcm\ImmutableHistory;

class VersionRepository
{
    protected $entityClassName;
    protected $entityManger;

    /**
     * @TODO allow per-resource-type locator validators to be injected and use them or use data models?
     * @TODO allow per-resouce-type content validators to be injected and use them or use data models?
     * @TODO set dates, user, and reason
     * @TODO replace all \Rcm\ImmutableHistory\Page\ImmutablePageVersion with interface in here
     * @TODO make userId and programmatic and any other fields that should be required, required
     * @TODO get statuses from constants? And actions too?
     * @TODO make an interface for this repo and make all callers use it
     *
     * Repository constructor.
     * @param $entityClassName
     * @param $entityManger
     */
    public function __construct($entityClassName, \Doctrine\ORM\EntityManager $entityManger)
    {
        $this->entityClassName = $entityClassName;
        $this->entityManger = $entityManger;
    }

    public function createUnpublishedFromNothing(LocatorInterface $locator, ContentInterface $content, $userId, $programmaticReason)
    {
        $newVersion = new $this->entityClassName(
            0, //@TODO
            null,
            new \DateTime(),
            VersionStatuses::UNPUBLISHED,
            'createUnpublishedFromNothing',
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function publishFromNothing(LocatorInterface $locator, ContentInterface $content, $userId, $programmaticReason)
    {
        $newVersion = new $this->entityClassName(
            0, //@TODO
            null,
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            'publishFromNothing',
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function publishFromExistingVersion($versionId, $userId, $programmaticReason)
    {
//        $newVersion = $this->entityManger->find($this->entityClassName, $versionId);
// @TODO how can this work in RCM?
    }

    public function depublish(LocatorInterface $locator, $userId, $programmaticReason)
    {
        $newVersion = new $this->entityClassName(
            0, //@TODO
            null,
            new \DateTime(),
            VersionStatuses::DEPUBLISHED,
            'depublish',
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
        $originalEntity = $this->getByLocator($oldLocator); //@TODO handle not found case

        $relocateDepublishVersion = new $this->entityClassName(
            0, //@TODO
            null,
            new \DateTime(),
            VersionStatuses::DEPUBLISHED,
            'relocateDepublish',
            $userId,
            $programmaticReason,
            $originalEntity->getLocator(),
            $originalEntity->getContent()
        );

        $this->entityManger->persist($relocateDepublishVersion);

        $relocatePublishVersion = new $this->entityClassName(
            0, //@TODO
            null,
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            'relocatePublish',
            $userId,
            $programmaticReason,
            $newLocator,
            $originalEntity->getContent()
        );

        $this->entityManger->persist($relocatePublishVersion);

        $this->entityManger->flush([$relocateDepublishVersion, $relocatePublishVersion]);
    }

    public function copy(LocatorInterface $relocateDepublishVersion, LocatorInterface $relocatePublishVersion)
    {
        $originalEntity = $this->getByLocator($oldLocator); //@TODO handle not found case
    }

    public function getOneByLocator(LocatorInterface $locator)
    {
        $doctrineRepo = $this->entityManger->getRepository($this->entityClassName);
        return $doctrineRepo->findOneBy($locator); //@TODO handle not found case
    }

    public function getUnpublishedVersionsByLocator(LocatorInterface $locator)
    {

    }

    public function getPublishedVersionsByLocator(LocatorInterface $locator)
    {

    }
}
