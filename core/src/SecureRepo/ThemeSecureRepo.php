<?php


namespace Rcm\SecureRepo;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\Exception\NotAllowedByBusinessLogicException;
use Rcm\Acl\Exception\NotAllowedBySecurityPropGenerationFailure;
use Rcm\Acl\GetCurrentUser;
use Rcm\Acl\IsAllowed;
use Rcm\Acl\ResourceName;
use Rcm\Acl\SecurityPropertiesProviderInterface;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\Entity\Container;
use Rcm\Entity\Country;
use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\PageNotFoundException;
use Rcm\Http\Response;
use Rcm\ImmutableHistory\Page\PageContent;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\PageLocator;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\ImmutableHistory\SiteWideContainer\ContainerContent;
use Rcm\ImmutableHistory\SiteWideContainer\SiteWideContainerLocator;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\Repository\Page as PageRepo;
use Rcm\SecurityPropertiesProvider\CountrySecurityPropertiesProvider;
use Rcm\Tracking\Exception\TrackingException;
use RcmAdmin\Exception\CannotDuplicateAnUnpublishedPageException;
use RcmMessage\Api\GetCurrentUserId;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\UserInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ThemeSecureRepo implements SecurityPropertiesProviderInterface
{
    protected $assertIsAllowed;
    protected $config;

    public function __construct(
        AssertIsAllowed $assertIsAllowed,
        array $config
    ) {
        $this->assertIsAllowed = $assertIsAllowed;
        $this->config = $config;
    }

    public function findAll()
    {
        $this->assertIsAllowed->__invoke(
            AclActions::READ,
            $this->findSecurityProperties([])
        );

        return $this->config['Rcm']['themes'];
    }

    public function findSecurityProperties($data): array
    {
        return [
            'type' => SecurityPropertyConstants::TYPE_ADMIN_TOOL,
            SecurityPropertyConstants::ADMIN_TOOL_TYPE_KEY
            => SecurityPropertyConstants::ADMIN_TOOL_TYPE_PAGE_THEME
        ];
    }
}
