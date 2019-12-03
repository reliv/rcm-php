<?php

namespace RcmUser\Acl\Service;

use Psr\Container\ContainerInterface;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\IsAllowedByUser;
use Rcm\Acl\NotAllowedException;
use RcmUser\Acl\Entity\AclRole;
use RcmUser\Acl\Entity\AclRule;
use RcmUser\Acl\Exception\RcmUserAclException;
use RcmUser\Event\EventProvider;
use RcmUser\Event\UserEventManager;
use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\UserRoleProperty;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Exception\ExceptionInterface;

/**
 * @deprecated is part of old ACL system and only still exists for BC constants support
 *
 * Class AuthorizeService
 * @package RcmUser\Acl\Service
 */
class AuthorizeService extends EventProvider
{
    const EVENT_IDENTIFIER = AuthorizeService::class;

    const EVENT_IS_ALLOWED_SUPER_ADMIN = 'aclIsAllowedSuperAdmin';
    const EVENT_IS_ALLOWED_TRUE = 'aclIsAllowedTrue';
    const EVENT_IS_ALLOWED_FALSE = 'aclIsAllowedFalse';
    const EVENT_IS_ALLOWED_ERROR = 'aclIsAllowedError';

    /**
     *
     * @var string RESOURCE_DELIMITER
     */
    const RESOURCE_DELIMITER = '.';
}
