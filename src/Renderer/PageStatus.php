<?php

namespace Rcm\Renderer;

/**
 * Class PageStatus
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageStatus
{
    const STATUS_OK = 200;
    const STATUS_NOT_FOUND = 410; // 404
    const STATUS_NOT_AUTHORIZED = 401;

    /**
     * @var array
     */
    protected $nameStatusMap = [];

    /**
     * Constructor.
     *
     * @param array $nameStatusMap
     */
    public function __construct(
        $nameStatusMap = []
    ) {
        $this->nameStatusMap = array_merge($nameStatusMap, $this->nameStatusMap);
    }

    /**
     * getStatus
     *
     * @param     $pageName
     * @param int $default
     *
     * @return int
     */
    public function getStatus($pageName, $default = self::STATUS_OK)
    {
        if (array_key_exists($pageName, $this->nameStatusMap)) {
            return $this->nameStatusMap[$pageName];
        }

        return $default;
    }

    /**
     * getOkStatus
     *
     * @return int
     */
    public function getOkStatus()
    {
        return self::STATUS_OK;
    }

    /**
     * getNotFoundStatus
     *
     * @return int
     */
    public function getNotFoundStatus()
    {
        return self::STATUS_NOT_FOUND;
    }

    /**
     * getNotAuthorizedStatus
     *
     * @return int
     */
    public function getNotAuthorizedStatus()
    {
        return self::STATUS_NOT_AUTHORIZED;
    }
}
