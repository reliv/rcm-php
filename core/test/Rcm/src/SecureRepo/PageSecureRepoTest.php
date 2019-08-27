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
    protected $currentUser;
    protected $siteRepo;
    protected $siteRepoSite;
    protected $siteRepoSiteId = 1111223;
    protected $notSiteRepoSiteId = 88887766;
    protected $siteRepoCountryIso3 = 'RUS';

    public function setup()
    {
        $this->pageRepo = m::mock(EntityRepository::class);
        $this->siteRepoSite = m::mock(Site::class);
        $this->siteRepoSite->allows('getCountryIso3')
            ->andReturn($this->siteRepoCountryIso3);
        $this->siteRepo = m::mock(EntityRepository::class);
        $this->siteRepo->allows('find')
            ->with($this->siteRepoSiteId)
            ->andReturn($this->siteRepoSite);
        $this->entityManager = m::mock(EntityManager::class);
        $this->entityManager->allows('getRepository')
            ->with(Page::class)
            ->andReturn($this->pageRepo);
        $this->entityManager->allows('getRepository')
            ->with(Revision::class)
            ->andReturn(m::mock(EntityRepository::class));
        $this->entityManager->allows('getRepository')
            ->with(Site::class)
            ->andReturn($this->siteRepo);
        $this->immuteblePageVersionRepo = m::mock(VersionRepositoryInterface::class);
        $this->immutableSiteWideContainerRepo = m::mock(VersionRepositoryInterface::class);
        $this->immutablePageContentFactory = m::mock(PageContentFactory::class);
        $this->rcmPageNameToPathname = m::mock(RcmPageNameToPathname::class);
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
        $pageRevisionId = 98769;

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::READ
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->entityManager->shouldNotReceive('flush');

        $this->pageSecureRepo->publishPageRevision($this->siteRepoSiteId, 'fun-page-1', 'p', $pageRevisionId);
    }

    public function testPublishPageRevisionRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {
        $pageRevisionId = 98769;

        $this->assertIsAllowed->allows('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::READ
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            });

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->entityManager->shouldNotReceive('flush');

        $this->pageSecureRepo->publishPageRevision($this->siteRepoSiteId, 'fun-page-1', 'p', $pageRevisionId);
    }

    public function testUpdatePublishedVersionOfPageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {
        $pageRevisionId = 98769;

        $this->assertIsAllowed->allows('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::READ
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            });

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->entityManager->shouldNotReceive('flush');

        $pageSite = m::mock(Site::class);
        $pageSite->allows('getSiteId')->andReturns($this->siteRepoSiteId);
        $page = m::mock(Page::class);
        $page->allows('getSite')->andReturns($pageSite);

        $this->pageSecureRepo->updatePublishedVersionOfPage($page, []);
    }

    public function testSavePageDraftRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {
        $pageRevisionId = 98769;

        $this->currentSite->allows('getSiteId')
            ->withNoArgs()
            ->andReturns($this->siteRepoSiteId);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
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
            ['siteId' => $this->siteRepoSiteId],
            function () {
            },
            $pageRevisionId
        );
    }

    public function testCreateNewPageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoCreateAccess()
    {
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->createNewPage($this->siteRepoSiteId, 'fun-page', 'p', []);
    }

    public function testCreateNewPageFromTemplateeRunsTheCorrectAclQueryAndThrowsIfAclSaysNoCreateAccess()
    {
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->createNewPageFromTemplate(['siteId' => $this->siteRepoSiteId]);
    }

    public function testDepublishPageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoDeleteAccess()
    {
        $pageRevisionId = 98769;

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::DELETE
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $pageSite = m::mock(Site::class);
        $pageSite->allows('getSiteId')->andReturns($this->siteRepoSiteId);
        $page = m::mock(Page::class);
        $page->allows('getSite')->andReturns($pageSite);

        $this->pageSecureRepo->depublishPage($page);
    }

    public function testFindPagesBySiteIdRunsTheCorrectAclQueryAndThrowsIfAclSaysNoReadAccess()
    {
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::READ
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->findPagesBySiteId($this->siteRepoSiteId);
    }

    public function testDuplicatePageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoReadAccess()
    {
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::READ
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $pageSite = m::mock(Site::class);
        $pageSite->allows('getSiteId')->andReturns($this->siteRepoSiteId);
        $page = m::mock(Page::class);
        $page->allows('getSite')->andReturns($pageSite);

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->duplicatePage(
            $page, $this->siteRepoSiteId, 'bobson1', 'p'
        );
    }

    public function testDuplicatePageRunsTheCorrectAclQueryAndThrowsIfAclSaysNoCreateAccess()
    {
        $this->assertIsAllowed->allows('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::READ
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            });

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual(
                        $props,
                        ['type' => 'content', 'contentType' => 'page', 'country' => $this->siteRepoCountryIso3]
                    );
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());


        $pageSite = m::mock(Site::class);
        $pageSite->allows('getSiteId')->andReturns($this->siteRepoSiteId);
        $page = m::mock(Page::class);
        $page->allows('getSite')->andReturns($pageSite);
        $page->allows('getPublishedRevision')->andReturns(234243);

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->pageSecureRepo->duplicatePage(
            $page, $this->siteRepoSiteId, 'bobson1', 'p'
        );
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
