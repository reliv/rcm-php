<?php

namespace RcmUser\Ui\Controller\Plugin;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * @deprecated Use RcmUserService->isAllowed()
 * RcmUserIsAllowed
 *
 * RcmUserIsAllowed
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Ui\Controller\Plugin
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserIsAllowed extends AbstractPlugin
{

    /**
     * @var AuthorizeService $authorizeService
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
