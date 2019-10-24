<?php

namespace Rcm\SwitchUser\Model;

use RcmUser\User\Entity\UserInterface;
use Reliv\RcmApiLib\Model\AbstractApiModel;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SuProperty extends AbstractApiModel
{
    /**
     * SU_PROPERTY
     */
    const SU_PROPERTY = 'suUser';

    /**
     * @var UserInterface
     */
    protected $suUser;

    /**
     * @param UserInterface $suUser
     */
    public function __construct(UserInterface $suUser)
    {
        $this->suUser = $suUser;
    }

    /**
     * getUserId
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->suUser;
    }

    /**
     * getUserId
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->suUser->getId();
    }

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->suUser->getUsername();
    }

    /**
     * getUserProperty
     *
     * @param string $propertyId
     * @param null   $default
     *
     * @return null
     */
    public function getUserProperty($propertyId, $default = null)
    {
        return $this->suUser->getProperty($propertyId, $default);
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = [])
    {
        $data = [];
        if (!in_array('username', $ignore)) {
            $data['username'] = $this->getUsername();
        }
        if (!in_array('userId', $ignore)) {
            $data['userId'] = $this->getUserId();
        }

        return $data;
    }
}
