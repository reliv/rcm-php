<?php

namespace RcmUser\Acl\Validator;

/**
 * Class ResourceIdValidator
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Service
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ResourceIdValidator
{
    /**
     * isValid
     *
     * @param $resourceId
     *
     * @return bool
     */
    public static function isValid($resourceId)
    {
        if (preg_match(
            '/[^a-z_\-0-9\.]/i',
            $resourceId
        )
        ) {
            return false;
        }

        return true;
    }
}
