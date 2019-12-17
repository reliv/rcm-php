<?php

namespace RcmUser\Api\Controller;

use Rcm\Http\NotAllowedResponseJsonZf2;
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

        if ($id == 'current') {
            $user = $rcmUserService->getCurrentUser();
        } else {
            return new NotAllowedResponseJsonZf2();
// Getting user profiles other than your own was disabled durring the ACL2 project because it didn't follow new rules
//            if (!$this->isReservedId($id)
//                && $this->isAllowed(
//                    RcmUserAclResourceProvider::RESOURCE_ID_USER,
//                    'read'
//                )
//            ) {
//                $user = $rcmUserService->getUserById($id);
//            }
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
