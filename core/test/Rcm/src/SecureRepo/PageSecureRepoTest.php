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
        $this->pageSecurityPropertiesProvider = m::mock(SecurityPropertiesProviderInterface::class);
        $this->currentSite = m::mock(Site::class);
        $this->currentUser = m::mock(UserInterface::class);
        $this->getCurrentUser = m::mock(GetCurrentUser::class);
        $this->getCurrentUser->allows('__invoke')
            ->with()
            ->andReturn($this->currentUser);
        $this->assertIsAllowed = m::mock(AssertIsAllowed::class);
    }

    public function tearDown()
    {
        m::close();
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

    public function testPublishPageRevisionRunsTheCorrectAclQueryAndThrowsIfAclSaysNoReadAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityPropertiesFromCreationData')
            ->with(['siteId' => $siteId])
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $securityProperties);
            })->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

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

        $this->pageSecureRepo->publishPageRevision($siteId, 'fun-page-1', 'p', $pageRevisionId);
    }

    public function testPublishPageRevisionRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityPropertiesFromCreationData')
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
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('publishPageRevision');
        $this->immuteblePageVersionRepo->shouldNotReceive('publish');

        $this->expectException(NotAllowedByQueryRunException::class);

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

        $this->pageSecureRepo->publishPageRevision($siteId, 'fun-page-1', 'p', $pageRevisionId);
    }

    public function testSavePageDraftRunsTheCorrectAclQueryAndThrowsIfAclSaysNoUpdateAccess()
    {

        $siteId = 7789;
        $pageRevisionId = 98769;

        $securityProperties = ['somePropKey' => 'somePropValue'];

        $this->pageSecurityPropertiesProvider->allows('findSecurityPropertiesFromCreationData')
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
            ->andThrow(new NotAllowedByQueryRunException());

        $this->pageRepo->shouldNotReceive('savePage');
        $this->immuteblePageVersionRepo->shouldNotReceive('createUnpublished');

        $this->expectException(NotAllowedByQueryRunException::class);

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

        $this->pageSecureRepo->savePageDraft(
            'fun-page-name',
            'p',
            ['siteId' => $siteId],
            function () {
            },
            $pageRevisionId
        );
    }
}
