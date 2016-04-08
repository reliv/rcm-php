<?php

namespace RcmAdmin\Controller;

use Rcm\Http\Response;
use RcmUser\Acl\Entity\AclRule;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * PageViewPermissionsController
 *
 * Page Permissions CRUD controller
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmAdmin\Controller
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
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
     * @param  string $id   $pageName
     * @param  array  $data $roles
     *
     * @return mixed
     */
    public function update($id, $data)
    {
        $this->aclDataService = $this->getServiceLocator()->get(
            'RcmUser\Acl\AclDataService'
        );

        $this->resourceProvider = $this->getServiceLocator()->get(
            'Rcm\Acl\ResourceProvider'
        );
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $this->pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        if (!is_array($data)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $this->getResponse();
        }

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $this->getServiceLocator()->get(
            'Rcm\Service\CurrentSite'
        );

        if (is_numeric($data['siteId']) && ($currentSite->getSiteId() == $data['siteId'])) {
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

        if (is_array($data['selectedRoles'])) {
            $selectedRoles = $data['selectedRoles'];
        } else {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);
            return $this->getResponse();
        }
        //CREATE RESOURCE ID
        $resourceId = 'sites.' . $siteId . '.pages.' . 'n' . '.' . $pageName;
        //ACCESS CHECK
        if (!$this->rcmIsAllowed($resourceId, 'edit') && !$this->isAllowed('pages', 'edit')) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
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
    public function deletePermissions($resourceId)
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
    public function addPermissions($roles, $resourceId)
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
    public function addPermission($roleId, $resourceId)
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
    public function isValidResourceId($resourceId)
    {
        $resource = $this->resourceProvider->getResource($resourceId);

        return true;
    }
}
