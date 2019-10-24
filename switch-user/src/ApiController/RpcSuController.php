<?php

namespace Rcm\SwitchUser\ApiController;

use RcmUser\User\Entity\UserInterface;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ExceptionApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RpcSuController extends BaseApiController
{
    /**
     * create
     *
     * @param array $data ['switchToUsername' => '{MY_ID}']
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function getList()
    {
        $service = $this->getSwitchUserService();

        $suUser = $service->getCurrentImpersonatorUser();

        $resultData = $this->buildResult(false, null);

        if (empty($suUser)) {
            return $this->getApiResponse($resultData);
        }

        $resultData = $this->buildResult(
            true,
            $this->getCurrentUser()
        );

        return $this->getApiResponse($resultData);
    }

    /**
     * create
     *
     * @param array $data ['switchToUsername' => '{MY_ID}']
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function create($data)
    {
        // Note: we can only ever check the current user for security reasons
        // If an SU user could keep switching,
        // there would be no way to know the original user
        $currentUser = $this->getCurrentUser();

        if (!$this->isAllowed($currentUser)) {
            return $this->getApiResponse(null, 401);
        }

        $service = $this->getSwitchUserService();

        $user = $service->getUser($data['switchToUsername']);

        $resultData = $this->buildResult(false, null);

        if (empty($user)) {
            return $this->getApiResponse(
                $resultData,
                400,
                new ApiMessage('httpStatus', 'The requested user could not be found.', 'rpcSu', '400', true)
            );
        }

        try {
            $result = $service->switchToUser($user);
        } catch (\Exception $exception) {
            return $this->getApiResponse(
                $resultData,
                500,
                new ExceptionApiMessage($exception)
            );
        }

        if (!$result->isSuccess()) {
            return $this->getApiResponse(
                $resultData,
                406,
                new ApiMessage('failure', $result->getMessage(), 'rpcSu', 'invalid')
            );
        }

        $resultData = $this->buildResult(true, $user);

        return $this->getApiResponse($resultData);
    }

    /**
     * buildResult
     *
     * @param bool               $isSu
     * @param UserInterface|null $impersonatedUser
     *
     * @return mixed
     */
    protected function buildResult($isSu, $impersonatedUser)
    {
        $data = [];
        $data['isSu'] = $isSu;
        $data['impersonatedUser'] = $impersonatedUser;
        $data['switchBackMethod'] = $this->getSwitchUserService()
            ->getSwitchBackMethod();

        return $data;
    }
}
