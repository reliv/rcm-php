<?php

namespace RcmUser\Api\Controller;

use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\InputFilter\LoginInputFilter;
use Reliv\RcmApiLib\Model\ApiMessage;
use Zend\Http\Response;

/**
 * Class UserController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Api\ApiController
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class UserController extends AbstractController
{
    /**
     * List of RPC methods that are passed as IDs
     *
     * @var array
     */
    protected $reservedIds
        = [
            'new',
            'current',
            'login',
            'logout',
        ];

    /**
     * isReservedId
     *
     * @param $id
     *
     * @return bool
     */
    protected function isReservedId($id)
    {
        return in_array($id, $this->reservedIds);
    }

    /**
     * get
     *
     * @param mixed $id
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function get($id)
    {
        $rcmUserService = $this->getRcmUserService();
        $user = null;

        if ($id == 'new') {
            $user = $rcmUserService->buildNewUser();
        }

        if ($id == 'current') {
            $user = $rcmUserService->getCurrentUser();
        }

        if (!$this->isReservedId($id)
            && $this->isAllowed(
                RcmUserAclResourceProvider::RESOURCE_ID_USER,
                'read'
            )
        ) {
            $user = $rcmUserService->getUserById($id);
        }

        if (empty($user)) {
            return $this->getApiResponse(
                $user,
                404
            );
        }

        return $this->getApiResponse(
            $user
        );
    }

    /**
     * create
     *
     * @param mixed $data
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function create($data)
    {
        $id = $this->params()->fromRoute('id');

        if ($id == 'login') {
            return $this->login($data);
        }

        if ($id == 'logout') {
            return $this->logout();
        }

        if ($id == 'new') {
            return $this->createNew($data);
        }

        // @todo Write create method
        return parent::create($data);
    }

    /**
     * login
     *
     * @param $data
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function login($data)
    {
        $rcmUserService = $this->getRcmUserService();
        $user = $rcmUserService->buildNewUser();

        $inputFilter = new LoginInputFilter();

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return $this->getApiResponse(
                null,
                400,
                $inputFilter
            );
        }

        $data = $inputFilter->getValues();

        $user->setUsername($data['username']);
        $user->setPassword($data['password']);

        $result = $rcmUserService->authenticate($user);

        if (!$result->isValid()) {
            $messages = $result->getMessages();
            foreach ($messages as $message) {
                $this->addApiMessage(
                    new ApiMessage(
                        'userLogin',
                        $message,
                        'userCredentials',
                        $result->getCode(),
                        true
                    )
                );
            }

            return $this->getApiResponse(
                null,
                400
            );
        }

        return $this->getApiResponse($result->getIdentity());
    }

    /**
     * logout
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function logout()
    {
        $rcmUserService = $this->getRcmUserService();

        $rcmUserService->clearIdentity();

        return $this->getApiResponse(null);
    }

    /**
     * createNew
     *
     * @param $data
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function createNew($data)
    {
        // @todo There may be cases where we want to expose this
        //       For now it is for admins only
        if (!$this->isAllowed(
            RcmUserAclResourceProvider::RESOURCE_ID_USER,
            'create'
        )
        ) {
            return $this->getApiResponse(
                null,
                401
            );
        }

        $rcmUserService = $this->getRcmUserService();

        $newUser = $rcmUserService->buildNewUser();

        // @todo we might filter here - there is low level filtering tho

        $newUser->populate($data);

        $result = $rcmUserService->createUser($newUser);

        if (!$result->isSuccess()) {
            return $this->getApiResponse(
                null,
                500,
                $result->getMessagesString()
            );
        }

        return $this->getApiResponse($result->getUser());
    }
}
