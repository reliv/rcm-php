<?php

namespace RcmUser\Api\Controller;

use Rcm\Http\NotAllowedResponseJsonZf2;
use RcmUser\Provider\RcmUserAclResourceProvider;
use RcmUser\InputFilter\LoginInputFilter;
use Reliv\RcmApiLib\Model\ApiMessage;
use Zend\Http\Response;

class UserController extends AbstractController
{
    /**
     * List of RPC methods that are passed as IDs
     *
     * @var array
     */
    protected $reservedIds
        = [
            'current',
            'login',
            'logout',
        ];

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

        if ($id == 'current') {
            $user = $rcmUserService->getCurrentUser();
        } else {
            return new NotAllowedResponseJsonZf2();
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

        return $this->methodNotAllowed();
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
}
