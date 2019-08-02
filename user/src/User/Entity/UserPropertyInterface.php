<?php

namespace RcmUser\User\Entity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface UserPropertyInterface extends \JsonSerializable
{

    /**
     * populate
     *
     * @param array|UserPropertyInterface $data data to populate with
     *
     * @return mixed
     */
    public function populate($data = []);
}
