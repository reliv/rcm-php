<?php

namespace RcmAdmin\Controller;

use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\IsAllowed;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\ResourceName;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\Http\Response;
use Rcm\RequestContext\RequestContext;
use Rcm\SecurityPropertiesProvider\PageSecurityPropertiesProvider;
use RcmUser\Acl\Entity\AclRule;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class PageViewPermissionsController extends AbstractRestfulController
{
    /**
     * @var
     */
    protected $siteId;

    /**
     * @var \Rcm\Acl\ResourceProvider $resourceProvider
     */
    protected $resourceProvider;

    /**
     * @var \RcmUser\Acl\Service\AclDataService $aclDataService
     */
    protected $aclDataService;

    /**
     * @var \Rcm\Repository\Page
     */
    protected $pageRepo;

    /**
     * Update an existing resource
     *
     * @param  string $id $pageName
     * @param  array $data $roles
     *
     * @return mixed
     */
    public function update($id, $data)
    {
        $this->aclDataService = $this->getServiceLocator()->get(
            \RcmUser\Acl\Service\AclDataService::class
        );

        $this->resourceProvider = $this->getServiceLocator()->get(
            \Rcm\Acl\ResourceProvider::class
        );

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $this->pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        // @todo Real input validation
        if (!is_array($data)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $this->getServiceLocator()->get(
            \Rcm\Service\CurrentSite::class
        );

        if (!empty($data['siteId']) && is_numeric($data['siteId']) && ($currentSite->getSiteId() == $data['siteId'])) {
            $siteId = $data['siteId'];
        } else {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }

        if (is_string($data['pageName'])) {
            $pageName = $data['pageName'];
        } else {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }

        if (is_string($data['pageType']) && strlen($data['pageType']) == '1') {
            $pageType = $data['pageType'];
        } else {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }

        $page = $this->pageRepo->getPageByName($currentSite, $pageName, $pageType);
        $isAllowed = $this->serviceLocator->get(RequestContext::class)->get(IsAllowed::class);
        $pageSecurityPropertiesProvider = $this->serviceLocator->get(PageSecurityPropertiesProvider::class);
        if (!$isAllowed->__invoke(// Check if we have access to CREATE the new site
            AclActions::UPDATE,
            $pageSecurityPropertiesProvider->findSecurityProperties([
                'siteId' => $page->getSiteId()
            ])
        )) {
            return new NotAllowedResponseJsonZf2();
        }

        if (is_array($data['selectedRoles'])) {
            $selectedRoles = $data['selectedRoles'];
        } else {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }
        //CREATE RESOURCE ID
        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        $resourceId = $resourceName->get(
            ResourceName::RESOURCE_SITES,
            $siteId,
            ResourceName::RESOURCE_PAGES,
            'n',
            $pageName
        );

        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->getServiceLocator()->get(
            RcmUserService::class
        );

        /** @oldControllerAclAccessCheckReplaced */

        /**
         * @var AssertIsAllowed $assertIsAllowed
         */
        $assertIsAllowed = $this->getServiceLocator()->get(RequestContext::class)
            ->get(AssertIsAllowed::class);

        try {
            $assertIsAllowed->__invoke(
                AclActions::UPDATE,
                [
                    'type' => SecurityPropertyConstants::TYPE_CONTENT,
                    'country' => $currentSite->getCountryIso3(),
                    SecurityPropertyConstants::CONTENT_TYPE_PAGE
                ]
            );
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        //IS PAGE VALID?
        $validPage = $this->pageRepo->isValid($currentSite, $pageName, $pageType);

        if (!$validPage) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);

            return $this->getResponse();
        }

        if (!$this->isValidResourceId($resourceId)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }

        //DELETE ALL PERMISSIONS
        $deleteAllPermissions = $this->deletePermissions($resourceId);

        if (!$deleteAllPermissions) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

            return $this->getResponse();
        }

        $newRoles = $this->addPermissions($selectedRoles, $resourceId);

        return new JsonModel($newRoles);
    }

    /**
     * deletePermissions
     *
     * @param $resourceId
     *
     * @return boolean
     */
    private function deletePermissions($resourceId)
    {
        $rules = $this->aclDataService->getRulesByResource($resourceId)
            ->getData();
        /** @var \RcmUser\Acl\Entity\AclRole $role */
        foreach ($rules as $rule) {
            $result = $this->aclDataService->deleteRule($rule);

            if (!$result->isSuccess()) {
                return false;
            }
        }

        return true;
    }

    /**
     * addPermissions
     *
     * @param $roles
     * @param $resourceId
     *
     * @return mixed|void
     */
    private function addPermissions($roles, $resourceId)
    {
        if (empty($roles)) {
            return;
        }

        $allRoles = $this->aclDataService->getAllRoles()->getData();

        // If all roles are selected, then no roles should be set (all roles allowed)
        // This assumes that the current rules have been deleted or they are empty
        if (count($roles) == count($allRoles)) {
            return $allRoles;
        }

        foreach ($roles as $role) {
            $this->addPermission($role['roleId'], $resourceId);
        }

        if (count($roles) > 0) {
            $this->aclDataService->createRule(
                $this->getAclRule('guest', $resourceId, 'deny')
            );
        }

        return $roles;
    }

    /**
     * addPermission
     *
     * @param $roleId
     * @param $resourceId
     *
     * @return void
     */
    private function addPermission($roleId, $resourceId)
    {
        $this->aclDataService->createRule(
            $this->getAclRule($roleId, $resourceId)
        );
    }

    /**
     * getAclRule
     *
     * @param        $roleId
     * @param        $resourceId
     * @param string $allowDeny
     *
     * @return AclRule
     * @throws \RcmUser\Exception\RcmUserException
     */
    protected function getAclRule($roleId, $resourceId, $allowDeny = 'allow')
    {
        $rule = new AclRule();
        $rule->setRoleId($roleId);
        $rule->setRule($allowDeny);
        $rule->setResourceId($resourceId);
        $rule->setPrivilege('read');

        return $rule;
    }

    /**
     * isValidResourceId
     *
     * @param $resourceId
     *
     * @return bool
     */
    private function isValidResourceId($resourceId)
    {
        $resource = $this->resourceProvider->getResource($resourceId);

        return true;
    }
}
