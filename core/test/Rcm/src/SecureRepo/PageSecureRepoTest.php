<?php

namespace RcmTest\SecureRepo;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\Exception\NotAllowedByQueryRunException;
use Rcm\Acl\GetCurrentUser;
use Rcm\Acl\SecurityPropertiesProviderInterface;
use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\SecureRepo\PageSecureRepo;
use \Mockery as m;
use Rcm\SecurityPropertiesProvider\PageSecurityPropertiesProvider;
use RcmUser\User\Entity\UserInterface;

class PageSecureRepoTest extends TestCase
{
    /** @var PageSecureRepo $pageSecureRepo */
    protected $pageSecureRepo;

    /**
     * @var \Rcm\Entity\Site
     */
    protected $currentSite;

    /**
     * @var \Rcm\Repository\Page
     */
    protected $pageRepo;

    protected $immuteblePageVersionRepo;

    protected $revisionRepo;

    protected $immutablePageContentFactory;

    protected $rcmPageNameToPathname;

    protected $entityManager;
    protected $immutableSiteWideContainerRepo;
    protected $getCurrentUser;
    protected $assertIsAllowed;
    protected $pageSecurityPropertiesProvider;
    protected $currentUser;

    public function setup()
    {
        $this->pageRepo = m::mock(EntityRepository::class);
        $this->entityManager = m::mock(EntityManager::class);
        $this->entityManager->allows('getRepository')
            ->with(Page::class)
            ->andReturn($this->pageRepo);
        $this->entityManager->allows('getRepository')
            ->with(Revision::class)
            ->andReturn(m::mock(EntityRepository::class));
        $this->immuteblePageVersionRepo = m::mock(VersionRepositoryInterface::class);
        $this->immutableSiteWideContainerRepo = m::mock(VersionRepositoryInterface::class);
        $this->immutablePageContentFactory = m::mock(PageContentFactory::class);
        $this->rcmPageNameToPathname = m::mock(RcmPageNameToPathname::class);
        $this->pageSecurityPropertiesProvider = m::mock(PageSecurityPropertiesProvider::class);
        $this->currentSite = m::mock(Site::class);
        $this->currentUser = m::mock(UserInterface::class);
        $this->getCurrentUser = m::mock(GetCurrentUser::class);
        $this->getCurrentUser->allows('__invoke')
            ->with()
            ->andReturn($this->currentUser);
        $this->assertIsAllowed = m::mock(AssertIsAllowed::class);

        $this->pageSecureRepo = new PageSecureRepo(
            $this->entityManager,
            $this->immuteblePageVersionRepo,
            $this->immutableSiteWideContainerRepo,
            $this->immutablePageContentFactory,
            $this->rcmPageNameToPathname,
            $this->pageSecurityPropertiesProvider,
            $this->currentSite,
            $this->getCurrentUser,
            $this->assertIsAllowed
        );
    }

    public function tearDown()
    {
        m::close();
    }

    public function testPublishPageRevisionRunsTheCorrectAclQueryAndThrowsIfAclSaysNoReadAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->entityManager->shouldNotReceive('flush');

        $this->pageSecureRepo->publishPageRevision($siteId, 'fun-page-1', 'p', $pageRevisionId);
    }

    public function testPublishPageRevisionRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->assertIsAllowed->allows('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $securityProperties);
            });

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->entityManager->shouldNotReceive('flush');

        $this->pageSecureRepo->publishPageRevision($siteId, 'fun-page-1', 'p', $pageRevisionId);
    }

    public function testUpdatePublishedVersionOfPageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->assertIsAllowed->allows('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $securityProperties);
            });

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->entityManager->shouldNotReceive('flush');

        $pageSite = m::mock(Site::class);
        $pageSite->allows('getSiteId')->andReturns($siteId);
        $page = m::mock(Page::class);
        $page->allows('getSite')->andReturns($pageSite);

        $this->pageSecureRepo->updatePublishedVersionOfPage($page, []);
    }

    public function testSavePageDraftRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->currentSite->allows('getSiteId')
            ->withNoArgs()
            ->andReturns($siteId);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->savePageDraft(
            'fun-page-name',
            'p',
            ['siteId' => $siteId],
            function () {
            },
            $pageRevisionId
        );
    }

    public function testCreateNewPageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoCreateAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->createNewPage($siteId, 'fun-page', 'p', []);
    }

    public function testCreateNewPageFromTemplateeRunsTheCorrectAclQueryAndThrowsIfAclSaysNoCreateAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->createNewPageFromTemplate(['siteId' => $siteId]);
    }

    public function testDepublishPageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoDeleteAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::DELETE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $pageSite = m::mock(Site::class);
        $pageSite->allows('getSiteId')->andReturns($siteId);
        $page = m::mock(Page::class);
        $page->allows('getSite')->andReturns($pageSite);

        $this->pageSecureRepo->depublishPage($page);
    }

    protected function arraysEqual($a, $b)
    {
        return (
            is_array($a)
            && is_array($b)
            && count($a) == count($b)
            && array_diff($a, $b) === array_diff($b, $a)
        );
    }
}
