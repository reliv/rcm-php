<?php

namespace RcmUser\Acl\Filter;

/**
 * Class ResourceIdFilter
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ResourceIdFilter
{
    /**
     * filter - set to lowercase to avoid overlaps
     *        - Allows nulls
     *
     * @param $resourceId
     *
     * @return string
     */
    public static function filter($resourceId)
    {
        if ($resourceId === null) {
            return null;
        }

        $resourceId = (string)$resourceId;

        return strtolower($resourceId);
    }
}
