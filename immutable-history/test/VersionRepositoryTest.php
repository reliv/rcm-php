<?php

namespace Rcm\ImmutableHistory\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Rcm\ImmutableHistory\ContentInterface;
use Rcm\ImmutableHistory\LocatorInterface;
use Rcm\ImmutableHistory\Page\ImmutablePageVersionEntity;
use Rcm\ImmutableHistory\Page\PageContent;
use Rcm\ImmutableHistory\Page\PageLocator;
use Rcm\ImmutableHistory\ResourceId\GenerateResourceIdInterface;
use Rcm\ImmutableHistory\VersionActions;
use Rcm\ImmutableHistory\VersionEntityInterface;
use Rcm\ImmutableHistory\VersionRepository;
use \Mockery;
use Rcm\ImmutableHistory\VersionStatuses;

class VersionRepositoryTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testPublishWithoutResourceIdOverrideAndWithFindablePreviousPublishedVersion()
    {
        $previousVersionEntityResourceId = 2727;
        $previousVersionEntity = Mockery::mock(VersionEntityInterface::class);
        $previousVersionEntity->allows('getResourceId')->andReturns($previousVersionEntityResourceId);
        $previousVersionEntity->allows('getStatus')->andReturns(VersionStatuses::PUBLISHED);

        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([$previousVersionEntity]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);

        $locatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $locator = Mockery::mock(PageLocator::class);
        $locator->allows('toArray')->andReturns($locatorAsArray);
        $locator->allows('getSiteId')->andReturns($locatorAsArray['siteId']);
        $locator->allows('getPathName')->andReturns($locatorAsArray['pathname']);

        $contentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $content = Mockery::mock(PageContent::class);
        $content->allows('toArrayForLongTermStorage')->andReturns($contentAsArray);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $previousVersionEntityResourceId,
            $locatorAsArray,
            $contentAsArray,
            $userId,
            $programaticReason
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::PUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::PUBLISH, $newVersion->getAction());
            $this->assertEquals($previousVersionEntityResourceId, $newVersion->getResourceId());
            $this->assertEquals($locatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($contentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->publish($locator, $content, $userId, $programaticReason);
    }

    public function testPublishWithoutResourceIdOverrideAndWithoutFindablePreviousPublishedVersion()
    {
        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generatedResourceId = 'f43b747a-63e6-4c8f-a05a-367220a9e30d';
        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);
        $generateResourceId->allows('__invoke')->andReturns($generatedResourceId);

        $locatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $locator = Mockery::mock(PageLocator::class);
        $locator->allows('toArray')->andReturns($locatorAsArray);
        $locator->allows('getSiteId')->andReturns($locatorAsArray['siteId']);
        $locator->allows('getPathName')->andReturns($locatorAsArray['pathname']);

        $contentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $content = Mockery::mock(PageContent::class);
        $content->allows('toArrayForLongTermStorage')->andReturns($contentAsArray);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $generatedResourceId,
            $locatorAsArray,
            $contentAsArray,
            $userId,
            $programaticReason
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::PUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::PUBLISH, $newVersion->getAction());
            $this->assertEquals($generatedResourceId, $newVersion->getResourceId());
            $this->assertEquals($locatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($contentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->publish($locator, $content, $userId, $programaticReason);
    }

    public function testCreateUnpublishedWithFindablePreviousPublishedVersion()
    {
        $previousVersionEntityResourceId = 2727;
        $previousVersionEntity = Mockery::mock(VersionEntityInterface::class);
        $previousVersionEntity->allows('getResourceId')->andReturns($previousVersionEntityResourceId);
        $previousVersionEntity->allows('getStatus')->andReturns(VersionStatuses::PUBLISHED);

        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([$previousVersionEntity]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);

        $locatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $locator = Mockery::mock(PageLocator::class);
        $locator->allows('toArray')->andReturns($locatorAsArray);
        $locator->allows('getSiteId')->andReturns($locatorAsArray['siteId']);
        $locator->allows('getPathName')->andReturns($locatorAsArray['pathname']);

        $contentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $content = Mockery::mock(PageContent::class);
        $content->allows('toArrayForLongTermStorage')->andReturns($contentAsArray);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $previousVersionEntityResourceId,
            $locatorAsArray,
            $contentAsArray,
            $userId,
            $programaticReason
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::UNPUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::CREATE_UNPUBLISHED, $newVersion->getAction());
            $this->assertEquals($previousVersionEntityResourceId, $newVersion->getResourceId());
            $this->assertEquals($locatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($contentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->createUnpublished($locator, $content, $userId, $programaticReason);
    }

    public function testCreateUnpublishedWithoutFindablePreviousPublishedVersion()
    {
        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generatedResourceId = 'f43b747a-63e6-4c8f-a05a-367220a9e30d';
        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);
        $generateResourceId->allows('__invoke')->andReturns($generatedResourceId);

        $locatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $locator = Mockery::mock(PageLocator::class);
        $locator->allows('toArray')->andReturns($locatorAsArray);
        $locator->allows('getSiteId')->andReturns($locatorAsArray['siteId']);
        $locator->allows('getPathName')->andReturns($locatorAsArray['pathname']);

        $contentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $content = Mockery::mock(PageContent::class);
        $content->allows('toArrayForLongTermStorage')->andReturns($contentAsArray);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $generatedResourceId,
            $locatorAsArray,
            $contentAsArray,
            $userId,
            $programaticReason
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::UNPUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::CREATE_UNPUBLISHED, $newVersion->getAction());
            $this->assertEquals($generatedResourceId, $newVersion->getResourceId());
            $this->assertEquals($locatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($contentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->createUnpublished($locator, $content, $userId, $programaticReason);
    }

    public function testDepublishWithFindablePreviousPublishedVersion()
    {
        $previousVersionEntityResourceId = 2727;
        $previousVersionEntity = Mockery::mock(VersionEntityInterface::class);
        $previousVersionEntity->allows('getResourceId')->andReturns($previousVersionEntityResourceId);
        $previousVersionEntity->allows('getStatus')->andReturns(VersionStatuses::PUBLISHED);

        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([$previousVersionEntity]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);

        $locatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $locator = Mockery::mock(PageLocator::class);
        $locator->allows('toArray')->andReturns($locatorAsArray);
        $locator->allows('getSiteId')->andReturns($locatorAsArray['siteId']);
        $locator->allows('getPathName')->andReturns($locatorAsArray['pathname']);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $previousVersionEntityResourceId,
            $locatorAsArray,
            $userId,
            $programaticReason
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::DEPUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::DEPUBLISH, $newVersion->getAction());
            $this->assertEquals($previousVersionEntityResourceId, $newVersion->getResourceId());
            $this->assertEquals($locatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals(null, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->depublish($locator, $userId, $programaticReason);
    }

    public function testDepublishWithoutFindablePreviousPublishedVersion()
    {
        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generatedResourceId = 'f43b747a-63e6-4c8f-a05a-367220a9e30d';
        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);
        $generateResourceId->allows('__invoke')->andReturns($generatedResourceId);

        $locatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $locator = Mockery::mock(PageLocator::class);
        $locator->allows('toArray')->andReturns($locatorAsArray);
        $locator->allows('getSiteId')->andReturns($locatorAsArray['siteId']);
        $locator->allows('getPathName')->andReturns($locatorAsArray['pathname']);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $generatedResourceId,
            $locatorAsArray,
            $userId,
            $programaticReason
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::DEPUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::DEPUBLISH, $newVersion->getAction());
            $this->assertEquals($generatedResourceId, $newVersion->getResourceId());
            $this->assertEquals($locatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals(null, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->depublish($locator, $userId, $programaticReason);
    }

    public function testDuplicate()
    {
        $previousVersionEntityResourceId = 2727;
        $previousVersionContentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $previousVersionEntity = Mockery::mock(VersionEntityInterface::class);
//        $previousVersionEntity->allows('getResourceId')->andReturns($previousVersionEntityResourceId);
        $previousVersionEntity->allows('getStatus')->andReturns(VersionStatuses::PUBLISHED);
        $previousVersionEntity->allows('getContentAsArray')->andReturns($previousVersionContentAsArray);

        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([$previousVersionEntity]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generatedResourceId = 'f43b747a-63e6-4c8f-a05a-367220a9e30d';
        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);
        $generateResourceId->allows('__invoke')->andReturns($generatedResourceId);

        $fromLocatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $fromLocator = Mockery::mock(PageLocator::class);
        $fromLocator->allows('toArray')->andReturns($fromLocatorAsArray);

        $toLocatorAsArray = ['siteId' => '27', 'pathname' => '/p/new-bob'];
        $toLocator = Mockery::mock(PageLocator::class);
        $toLocator->allows('getSiteId')->andReturns($toLocatorAsArray['siteId']);
        $toLocator->allows('getPathName')->andReturns($toLocatorAsArray['pathname']);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $toLocatorAsArray,
            $userId,
            $programaticReason,
            $previousVersionContentAsArray,
            $generatedResourceId
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::PUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::DUPLICATE, $newVersion->getAction());
            $this->assertEquals($generatedResourceId, $newVersion->getResourceId());
            $this->assertEquals($toLocatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($previousVersionContentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->duplicate($fromLocator, $toLocator, $userId, $programaticReason);
    }

    public function testRelocate()
    {
        $previousVersionEntityResourceId = 2727;
        $previousVersionContentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $previousVersionEntity = Mockery::mock(VersionEntityInterface::class);
        $previousVersionEntity->allows('getResourceId')->andReturns($previousVersionEntityResourceId);
        $previousVersionEntity->allows('getStatus')->andReturns(VersionStatuses::PUBLISHED);
        $previousVersionEntity->allows('getContentAsArray')->andReturns($previousVersionContentAsArray);

        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([$previousVersionEntity]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManagerConnection = Mockery::mock(Connection::class);

        $entityManager = Mockery::spy(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);
        $entityManager->allows('getConnection')->andReturns($entityManagerConnection)->twice();

        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);

        $fromLocatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $fromLocator = Mockery::mock(PageLocator::class);
        $fromLocator->allows('toArray')->andReturns($fromLocatorAsArray);
        $fromLocator->allows('getSiteId')->andReturns($fromLocatorAsArray['siteId']);
        $fromLocator->allows('getPathName')->andReturns($fromLocatorAsArray['pathname']);

        $toLocatorAsArray = ['siteId' => '27', 'pathname' => '/p/new-bob'];
        $toLocator = Mockery::mock(PageLocator::class);
        $toLocator->allows('getSiteId')->andReturns($toLocatorAsArray['siteId']);
        $toLocator->allows('getPathName')->andReturns($toLocatorAsArray['pathname']);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkDepublishedVersionEntity = function ($newVersion) use (
            $fromLocatorAsArray,
            $toLocatorAsArray,
            $userId,
            $programaticReason,
            $previousVersionContentAsArray,
            $previousVersionEntityResourceId
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::DEPUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::RELOCATE_DEPUBLISH, $newVersion->getAction());
            $this->assertEquals($previousVersionEntityResourceId, $newVersion->getResourceId());
            $this->assertEquals($fromLocatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($previousVersionContentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };


        $checkNewVersionEntity = function ($newVersion) use (
            $toLocatorAsArray,
            $userId,
            $programaticReason,
            $previousVersionContentAsArray,
            $previousVersionEntityResourceId
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::PUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::RELOCATE_PUBLISH, $newVersion->getAction());
            $this->assertEquals($previousVersionEntityResourceId, $newVersion->getResourceId());
            $this->assertEquals($toLocatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($previousVersionContentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManagerConnection->expects('beginTransaction')->ordered()->globally();

        $entityManager->expects('persist')->withArgs($checkDepublishedVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkDepublishedVersionEntity)->ordered();

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $entityManagerConnection->expects('commit')->ordered()->globally();

        $unit->relocate($fromLocator, $toLocator, $userId, $programaticReason);
    }

    public function testDuplicateBcWithoutFindablePreviousVersion()
    {
        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->allows('toArray')->andReturns([]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->allows('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->allows('getRepository')->andReturns($entityRepo);

        $generatedResourceId = 'f43b747a-63e6-4c8f-a05a-367220a9e30d';
        $generateResourceId = Mockery::mock(GenerateResourceIdInterface::class);
        $generateResourceId->allows('__invoke')->andReturns($generatedResourceId);

        $fromLocatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $fromLocator = Mockery::mock(PageLocator::class);
        $fromLocator->allows('toArray')->andReturns($fromLocatorAsArray);

        $toLocatorAsArray = ['siteId' => '27', 'pathname' => '/p/new-bob'];
        $toLocator = Mockery::mock(PageLocator::class);
        $toLocator->allows('getSiteId')->andReturns($toLocatorAsArray['siteId']);
        $toLocator->allows('getPathName')->andReturns($toLocatorAsArray['pathname']);

        $contentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $content = Mockery::mock(PageContent::class);
        $content->allows('toArrayForLongTermStorage')->andReturns($contentAsArray);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateResourceId
        );

        $checkNewVersionEntity = function ($newVersion) use (
            $generatedResourceId,
            $toLocatorAsArray,
            $contentAsArray,
            $userId,
            $programaticReason
        ) {
            /**
             * @var VersionEntityInterface $newVersion
             */
            $this->assertInstanceOf(VersionEntityInterface::class, $newVersion);
            $this->assertEquals(VersionStatuses::PUBLISHED, $newVersion->getStatus());
            $this->assertEquals(VersionActions::DUPLICATE, $newVersion->getAction());
            $this->assertEquals($generatedResourceId, $newVersion->getResourceId());
            $this->assertEquals($toLocatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($contentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals(
                $programaticReason . ',duplicateBc::sourceDidNotExist',
                $newVersion->getProgrammaticReason()
            );

            return true;
        };

        $entityManager->expects('persist')->withArgs($checkNewVersionEntity)->ordered();
        $entityManager->expects('flush')->withArgs($checkNewVersionEntity)->ordered();

        $unit->duplicateBc($fromLocator, $toLocator, $content, $userId, $programaticReason);
    }
}
