<?php

namespace RcmTest\SecureRepo;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\Exception\NotAllowedByQueryRunException;
use Rcm\Acl\GetCurrentUser;
use Rcm\Acl\SecurityPropertiesProviderInterface;
use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Entity\Site;
use Rcm\Entity\Revision;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Site\RcmSiteNameToPathname;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\SecureRepo\PageSecureRepo;
use Rcm\SecureRepo\SiteSecureRepo;
use \Mockery as m;
use Rcm\SecureRepo\SiteSecureRepoPaginatorFactory;
use Rcm\SecurityPropertiesProvider\SiteSecurityPropertiesProvider;
use Rcm\Service\LayoutManager;
use RcmUser\User\Entity\UserInterface;
use Zend\Paginator\Paginator;

class SiteSecureRepoTest extends TestCase
{
    /** @var SiteSecureRepo $siteSecureRepo */
    protected $siteSecureRepo;

    /**
     * @var \Rcm\Entity\Site
     */
    protected $currentSite;

    /**
     * @var \Rcm\Repository\Site
     */
    protected $siteRepo;

    protected $immutebleSiteVersionRepo;

    protected $revisionRepo;
    protected $languageRepo;

    protected $immutablePageContentFactory;

    protected $entityManager;
    protected $immutableSiteWideContainerRepo;
    protected $getCurrentUser;
    protected $assertIsAllowed;
    protected $siteSecurityPropertiesProvider;
    protected $currentUser;
    protected $config;
    protected $pageSecureRepo;
    protected $paginatorFactory;
    protected $paginator;
    protected $countryRepo;

    public function setup()
    {
        $this->siteRepo = m::mock(EntityRepository::class);
        $this->languageRepo = m::mock(Language::class);
        $this->countryRepo = m::mock(Country::class);
        $this->entityManager = m::mock(EntityManager::class);
        $this->entityManager->allows('getRepository')
            ->with(Site::class)
            ->andReturn($this->siteRepo);
        $this->entityManager->allows('getRepository')
            ->with(Revision::class)
            ->andReturn(m::mock(EntityRepository::class));
        $this->entityManager->allows('getRepository')
            ->with(Language::class)
            ->andReturn($this->languageRepo);
        $this->entityManager->allows('getRepository')
            ->with(Country::class)
            ->andReturn($this->countryRepo);
        $this->pageSecureRepo = m::mock(PageSecureRepo::class);
        $this->immutebleSiteVersionRepo = m::mock(VersionRepositoryInterface::class);
        $this->immutableSiteWideContainerRepo = m::mock(VersionRepositoryInterface::class);
        $this->immutablePageContentFactory = m::mock(PageContentFactory::class);
        $this->siteSecurityPropertiesProvider = m::mock(SiteSecurityPropertiesProvider::class);
        $this->currentSite = m::mock(Site::class);
        $this->currentUser = m::mock(UserInterface::class);
        $this->getCurrentUser = m::mock(GetCurrentUser::class);
        $this->getCurrentUser->allows('__invoke')
            ->with()
            ->andReturn($this->currentUser);
        $this->assertIsAllowed = m::mock(AssertIsAllowed::class);
        $this->config = [
            'rcmAdmin' => [
                'defaultSiteSettings' => [
                    'countryId' => 'USA',
                    'languageIso6392t' => 'en'
                ]
            ]
        ];
        $this->paginator = m::mock(Paginator::class);
        $this->paginatorFactory = m::mock(SiteSecureRepoPaginatorFactory::class);
        $this->paginatorFactory->allows('__invoke')->andReturn($this->paginator);

        $this->siteSecureRepo = new SiteSecureRepo(
            $this->config,
            $this->entityManager,
            $this->pageSecureRepo,
            $this->immutebleSiteVersionRepo,
            $this->immutableSiteWideContainerRepo,
            $this->immutablePageContentFactory,
            $this->getCurrentUser,
            $this->siteSecurityPropertiesProvider,
            $this->assertIsAllowed,
            $this->paginatorFactory,
            $this->currentSite,
            m::mock(LayoutManager::class)
        );
    }

    public function tearDown()
    {
        m::close();
    }

//    protected function arraysEqual($a, $b)
//    {
//        return (
//            is_array($a)
//            && is_array($b)
//            && count($a) == count($b)
//            && array_diff($a, $b) === array_diff($b, $a)
//        );
//    }

    public function testGetListCallsAssertIsAllowedProperlyAndOmitsSitesForWhichAclSaysTheUserHasNoReadAccess()
    {
        $queryBuilder = m::mock(QueryBuilder::class);
        $queryBuilder->allows('select')->andReturn($queryBuilder);
        $queryBuilder->allows('leftJoin')->andReturn($queryBuilder);
        $queryBuilder->allows('getQuery')->andReturn($queryBuilder);
        $this->siteRepo->allows('createQueryBuilder')->andReturn($queryBuilder);
        $this->paginator->allows('setDefaultItemCountPerPage');

        $usaSite = m::mock(Site::class);
        $usaSite->shouldReceive('getCountryIso3')->once()->andReturn('USA');
        $usaSite->shouldReceive('toArray')->once()->andReturn(['countryIso3' => 'USA']);

        $deuSite = m::mock(Site::class);
        $deuSite->shouldReceive('getCountryIso3')->once()->andReturn('DEU');
        $deuSite->shouldNotReceive('toArray');

        $canSite = m::mock(Site::class);
        $canSite->shouldReceive('getCountryIso3')->once()->andReturn('CAN');
        $canSite->shouldReceive('toArray')->once()->andReturn(['countryIso3' => 'CAN']);

        $this->paginator->allows('getCurrentItems')->andReturn([$usaSite, $deuSite, $canSite]);
        $this->paginator->allows('getTotalItemCount')->andReturn(3);
        $this->paginator->allows('count')->andReturn(3);
        $this->paginator->allows('getCurrentPageNumber')->andReturn(1);

        $usaSecurityProperties = ['country' => 'USA'];
        $deuSecurityProperties = ['country' => 'DEU'];
        $canSecurityProperties = ['country' => 'CAN'];

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'USA'])
            ->once()
            ->andReturn($usaSecurityProperties);
        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'DEU'])
            ->once()
            ->andReturn($deuSecurityProperties);
        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'CAN'])
            ->once()
            ->andReturn($canSecurityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($usaSecurityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $usaSecurityProperties);
            })
            ->once();

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($deuSecurityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $deuSecurityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException()); //Not allowed so should be omited from results

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($canSecurityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $canSecurityProperties);
            })
            ->once();

        $sites = $this->siteSecureRepo->getList(null, null, null);

        //Assert that DEU was omited from the site result list
        $this->assertEquals(2, count($sites['items']));
        $this->assertEquals('USA', $sites['items'][0]['countryIso3']);
        $this->assertEquals('CAN', $sites['items'][1]['countryIso3']);
    }

    public function testGetWithIdEqualsDefaultCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveReadAccess()
    {
        $enLanguage = m::mock(Language::class);
        $enLanguage->allows('getLanguageId')->andReturn('en');
        $usaCountry = m::mock(Country::class);
        $usaCountry->allows('getIso3')->andReturn('USA');
        $this->languageRepo->allows('getLanguageByString')->andReturn($enLanguage);
        $this->countryRepo->allows('find')->with('USA')->andReturn($usaCountry);
        $this->currentUser->allows('getId')->andReturn(87686788);

        $securityProperties = ['someSecurityPropKey' => 'someSecurityPropValue'];

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'USA'])
            ->once()
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->get('default');
    }

    public function testGetWithIdEqualsCurrentCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveReadAccess()
    {
        $this->currentSite->shouldReceive('getCountryIso3')->once()->andReturn('USA');

        $securityProperties = ['someSecurityPropKey' => 'someSecurityPropValue'];

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'USA'])
            ->once()
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->get('current');
    }

    public function testGetCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveReadAccess()
    {
        $site = m::mock(Site::class);
        $site->shouldReceive('getCountryIso3')->once()->andReturn('CAN');
        $this->siteRepo->shouldReceive('find')->once()->andReturn($site);

        $securityProperties = ['someSecurityPropKey' => 'someSecurityPropValue'];

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'CAN'])
            ->once()
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->get(1337);
    }

    public function testCreateSingleFromArrayCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveCreateAccess()
    {
        $securityProperties = ['someSecurityPropKey' => 'someSecurityPropValue'];

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'DEU'])
            ->once()
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->createSingleFromArray(['countryId' => 'DEU']);
    }

    /**
     * This test unsures we cannot change an existing site TO A COUNTRY for which we don't have UPDATE access
     */
    public function testUpdateCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveUpdateAccessBasedOnRequestSiteCountry()
    {
        $securityProperties = ['someSecurityPropKey' => 'someSecurityPropValue'];

        $this->siteRepo->allows('isValidSiteId')->andReturn(true);

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'DEU'])
            ->once()
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->update(1337, ['countryId' => 'DEU']);
    }

    /**
     * This test unsures we cannot change an existing site OF A COUNTRY for which we don't have UPDATE access
     */
    public function testUpdateCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveUpdateAccessBasedOnDatebaseSiteCountry()
    {
        $siteId = 13371337;
        $deuSecurityProperties = ['someSecurityPropKey' => 'someSecurityPropValueDeu'];//Request Site
        $canSecurityProperties = ['someSecurityPropKey' => 'someSecurityPropValueCan'];//Database Site

        $this->siteRepo->allows('isValidSiteId')->andReturn(true);

        $databaseSite = m::mock(Site::class);
        $databaseSite->shouldReceive('getCountryIso3')->once()->andReturn('CAN');

        $this->siteRepo
            ->shouldReceive('find')
            ->with($siteId)
            ->once()
            ->andReturn($databaseSite);

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'DEU'])
            ->once()
            ->andReturn($deuSecurityProperties);

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'CAN'])
            ->once()
            ->andReturn($canSecurityProperties);

        //We have access to the request-data site country so DON'T throw here
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($deuSecurityProperties) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual($props, $deuSecurityProperties);
            })
            ->once();

        //We don't have access to the database site country so DO throw here.
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($canSecurityProperties) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual($props, $canSecurityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->update($siteId, ['countryId' => 'DEU', 'status' => 'A']);
    }

    public function testCreateSiteCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveCreateAccess()
    {
        $securityProperties = ['someSecurityPropKey' => 'someSecurityPropValue'];

        $site = m::mock(Site::class);
        $site->shouldReceive('getCountryIso3')->once()->andReturn('GBR');

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'GBR'])
            ->once()
            ->andReturn($securityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($securityProperties) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual($props, $securityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->createSite($site);
    }

    public function testDuplicateAndUpdateCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveCreateAccessToRequestDataCountry(
    )
    {
        $deuSecurityProperties = ['someSecurityPropKey' => 'someSecurityPropValueDeu'];//Request Site
        $canSecurityProperties = ['someSecurityPropKey' => 'someSecurityPropValueCan'];//Database Site

        $this->siteRepo->allows('isValidSiteId')->andReturn(true);

        $databaseSite = m::mock(Site::class);
        $databaseSite->shouldReceive('getCountryIso3')->once()->andReturn('CAN');


        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'DEU'])
            ->once()
            ->andReturn($deuSecurityProperties);

        $this->siteSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['countryIso3' => 'CAN'])
            ->once()
            ->andReturn($canSecurityProperties);

        //We have access to the DATABASE site country so DON'T throw here
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($canSecurityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $canSecurityProperties);
            })
            ->once();

        //We don't have access to the REQUEST site country so DO throw here.
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($deuSecurityProperties) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual($props, $deuSecurityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());;


        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->duplicateAndUpdate(
            $databaseSite,
            'somewhereelse.com',
            ['countryId' => 'DEU', 'status' => 'A']
        );
    }

    public function testDuplicateAndUpdateCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveReadAccessToDatabaseSiteCountry(
    )
    {
        $deuSecurityProperties = ['someSecurityPropKey' => 'someSecurityPropValueDeu'];//Request Site
        $canSecurityProperties = ['someSecurityPropKey' => 'someSecurityPropValueCan'];//Database Site

        $this->siteRepo->allows('isValidSiteId')->andReturn(true);

        $databaseSite = m::mock(Site::class);
        $databaseSite->shouldReceive('getCountryIso3')->once()->andReturn('CAN');


        $this->siteSecurityPropertiesProvider->allows('findSecurityProperties')
            ->with(['countryIso3' => 'DEU'])
            ->andReturn($deuSecurityProperties);

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'CAN'])
            ->once()
            ->andReturn($canSecurityProperties);

        //We don't have access to the DATABASE site country so DO throw here
        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($canSecurityProperties) {
                return $action === AclActions::READ
                    && $this->arraysEqual($props, $canSecurityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());

        //We do have access to the REQUEST site country so DON't throw here.
        $this->assertIsAllowed->allows('__invoke')
            ->withArgs(function ($action, $props) use ($deuSecurityProperties) {
                return $action === AclActions::CREATE
                    && $this->arraysEqual($props, $deuSecurityProperties);
            });

        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->duplicateAndUpdate(
            $databaseSite,
            'somewhereelse.com',
            ['countryId' => 'DEU', 'status' => 'A']
        );
    }

    public function testChangeSiteDomainNameCallsAssertIsAllowedProperlyAndThrowsWhenDoNotHaveUpdateAccessToDatabaseSiteCountry(
    )
    {
        $canSecurityProperties = ['someSecurityPropKey' => 'someSecurityPropValueCan'];//Database Site

        $this->siteRepo->allows('isValidSiteId')->andReturn(true);

        $databaseSite = m::mock(Site::class);
        $databaseSite->shouldReceive('getCountryIso3')->once()->andReturn('CAN');

        $this->siteSecurityPropertiesProvider->shouldReceive('findSecurityProperties')
            ->with(['countryIso3' => 'CAN'])
            ->once()
            ->andReturn($canSecurityProperties);

        $this->assertIsAllowed->shouldReceive('__invoke')
            ->withArgs(function ($action, $props) use ($canSecurityProperties) {
                return $action === AclActions::UPDATE
                    && $this->arraysEqual($props, $canSecurityProperties);
            })
            ->once()
            ->andThrow(new NotAllowedByQueryRunException());


        $this->entityManager->shouldNotReceive('flush');

        $this->expectException(NotAllowedByQueryRunException::class);

        $this->siteSecureRepo->changeSiteDomainName($databaseSite, 'somewhereelse.com');
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
