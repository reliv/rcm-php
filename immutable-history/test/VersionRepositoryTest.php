<?php

namespace Rcm\ImmutableHistory\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Rcm\ImmutableHistory\ContentInterface;
use Rcm\ImmutableHistory\LocatorInterface;
use Rcm\ImmutableHistory\Page\ImmutablePageVersionEntity;
use Rcm\ImmutableHistory\Page\PageContent;
use Rcm\ImmutableHistory\Page\PageLocator;
use Rcm\ImmutableHistory\ResourceId\GenerateResourceIdInterface;
use Rcm\ImmutableHistory\VersionEntityInterface;
use Rcm\ImmutableHistory\VersionRepository;
use \Mockery;
use Rcm\ImmutableHistory\VersionStatuses;

class VersionRepositoryTest extends TestCase
{
    public function testPublishWithoutResourceIdOverrideAndWithFindablePreviousPublishedVersion()
    {
        $previousVersionEntityResourceId = 2727;
        $previousVersionEntity = Mockery::mock(VersionEntityInterface::class);
        $previousVersionEntity->expects('getResourceId')->andReturns($previousVersionEntityResourceId);
        $previousVersionEntity->expects('getStatus')->andReturns(VersionStatuses::PUBLISHED);

        $entityRepoResults = Mockery::mock(ArrayCollection::class);
        $entityRepoResults->expects('toArray')->andReturns([$previousVersionEntity]);

        $entityRepo = Mockery::mock(EntityRepository::class);
        $entityRepo->expects('matching')->andReturns($entityRepoResults);

        $entityManager = Mockery::mock(EntityManager::class);
        $entityManager->expects('getRepository')->andReturns($entityRepo);

        $generateRsourceId = Mockery::mock(GenerateResourceIdInterface::class);

        $locatorAsArray = ['siteId' => '27', 'pathname' => '/p/bob'];
        $locator = Mockery::mock(PageLocator::class);
        $locator->expects('toArray')->andReturns($locatorAsArray);
        $locator->expects('getSiteId')->andReturns($locatorAsArray['siteId']);
        $locator->expects('getPathName')->andReturns($locatorAsArray['pathname']);

        $contentAsArray = ['fun' => 'example', 'content' => 'yeah'];
        $content = Mockery::mock(PageContent::class);
        $content->expects('toArrayForLongTermStorage')->andReturns($contentAsArray);

        $userId = 827364244;
        $programaticReason = __CLASS__ . '::' . __FUNCTION__;

        $unit = new VersionRepository(
            ImmutablePageVersionEntity::class,
            $entityManager,
            $generateRsourceId
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
            $this->assertEquals($previousVersionEntityResourceId, $newVersion->getResourceId());
            $this->assertEquals($locatorAsArray, $newVersion->getLocator()->toArray());
            $this->assertEquals($contentAsArray, $newVersion->getContentAsArray());
            $this->assertEquals($userId, $newVersion->getUserId());
            $this->assertEquals($programaticReason, $newVersion->getProgrammaticReason());

            return true;
        };

        $entityManager->shouldReceive('persist')->withArgs($checkNewVersionEntity);
        $entityManager->shouldReceive('flush')->withArgs($checkNewVersionEntity);

        $unit->publish($locator, $content, $userId, $programaticReason);

    }
}
