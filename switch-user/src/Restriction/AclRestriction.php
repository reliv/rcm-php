<?php

namespace Rcm\SwitchUser\Restriction;

use RcmUser\Api\Acl\IsUserAllowed;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AclRestriction implements Restriction
{
    /**
     * @var array
     */
    protected $aclConfig;

    /**
     * @var IsUserAllowed
     */
    protected $isUserAllowed;

    /**
     * @param array         $config
     * @param IsUserAllowed $isUserAllowed
     */
    public function __construct($config, IsUserAllowed $isUserAllowed)
    {
        $this->aclConfig = $config['Rcm\\SwitchUser']['acl'];
        $this->isUserAllowed = $isUserAllowed;
    }

    /**
     * allowed
     *
     * @param UserInterface $adminUser
     * @param UserInterface $targetUser
     *
     * @return RestrictionResult
     */
    public function allowed(UserInterface $adminUser, UserInterface $targetUser)
    {
        $isAllowed = $this->isUserAllowed->__invoke(
            $adminUser,
            $this->aclConfig['resourceId'],
            $this->aclConfig['privilege']
        );

        if (!$isAllowed) {
            return new RestrictionResult(
                false,
                'Current user cannot switch user'
            );
        }

        return new RestrictionResult(true);
    }
}
