<?php

namespace Rcm\Service;

/**
 * Class PageTypes
 * n=Normal, t=Template, z=System, deleted-{originalPageType}
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageTypes
{
    const NORMAL = 'n';
    const TEMPLATE = 't';
    const SYSTEM = 'z';
    const DELETED = 'deleted-{originalPageType}';

    protected $pageTypes
        = [
            self::NORMAL,
            self::TEMPLATE,
            self::SYSTEM,
        ];

    /**
     * getDeletedType
     *
     * @param string $type
     *
     * @return mixed
     */
    public static function getDeletedType($type = self::NORMAL)
    {
        return str_replace(self::DELETED, $type, '{originalPageType}');
    }
}
