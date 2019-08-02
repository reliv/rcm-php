<?php

namespace RcmUser\Ui\View\Helper;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\View\Helper\AbstractHelper;

/**
 * @deprecated Use RcmUserService->isAllowed()
 * Class RcmUserIsAllowed
 *
 * RcmUserIsAllowed
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Ui\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserIsAllowed extends AbstractHelper
{

    /**
     * @var AuthorizeService
     */
    protected $authorizeService;

    /**
     * @var UserAuthenticationService $userAuthService
     */
    protected $userAuthService;

    /**
     * __construct
     *
     * @param AuthorizeService          $authorizeService authorizeService
     * @param UserAuthenticationService $userAuthService  userAuthService
     */
    public function __construct(
        AuthorizeService $authorizeService,
        UserAuthenticationService $userAuthService
    ) {
        $this->authorizeService = $authorizeService;
        $this->userAuthService = $userAuthService;
    }

    /**
     * @deprecated Use RcmUserService->isAllowed()
     * __invoke
     *
     * @param string $resourceId resourceId
     * @param string $privilege  privilege
     * @param string $providerId providerId
     *
     * @return bool
     */
    public function __invoke(
        $resourceId,
        $privilege = null,
        $providerId = null
    ) {
        $user = $this->userAuthService->getIdentity();

        return $this->authorizeService->isAllowed(
            $resourceId,
            $privilege,
            $providerId,
            $user
        );
    }
}
