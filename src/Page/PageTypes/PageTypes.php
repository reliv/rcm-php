<?php

namespace Rcm\Page\PageTypes;

/**
 * @GammaRelease
 * Class PageTypes
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageTypes
{
    /**
     * n=Normal
     */
    const NORMAL = 'n';
    /**
     * t=Template,
     */
    const TEMPLATE = 't';
    /**
     * z=System
     */
    const SYSTEM = 'z';
    /*
     * deleted-{originalPageType}
     */
    const DELETED = 'deleted-{originalPageType}';

    /**
     * @var array
     */
    protected static $defaultTypesAvailable
        = [
            PageTypes::NORMAL => [
                'type' => PageTypes::NORMAL,
                'title' => 'Normal Page',
                'canClone' => true,
            ],
            PageTypes::TEMPLATE => [
                'type' => PageTypes::TEMPLATE,
                'title' => 'Template Page',
                'canClone' => true,
            ],
            PageTypes::SYSTEM => [
                'type' => PageTypes::SYSTEM,
                'title' => 'System Page',
                'canClone' => true,
            ],
        ];

    /**
     * @var array
     */
    protected $pageTypesAvailable = [];

    /**
     * getDefaultTypesAvailable
     *
     * @return array
     */
    public static function getDefaultTypesAvailable()
    {
        return self::$defaultTypesAvailable;
    }

    /**
     * Constructor.
     *
     * @param $pageTypes
     */
    public function __construct($pageTypes = [])
    {
        $this->pageTypesAvailable = array_replace_recursive(
            self::$defaultTypesAvailable,
            $pageTypes
        );
    }

    /**
     * get
     *
     * @param string $key
     * @param string $type
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get($key, $type = self::NORMAL, $default = null)
    {
        $typeConfig = $this->getTypeConfig($type);

        if (array_key_exists($key, $typeConfig)) {
            return $typeConfig[$key];
        }

        return $default;
    }

    /**
     * getTypeConfig
     *
     * @param string $type
     * @param array  $default
     *
     * @return array
     */
    public function getTypeConfig($type = self::NORMAL, $default = [])
    {
        if ($this->isTypeAvailable($type)) {
            return $this->pageTypesAvailable[$type];
        }

        return $default;
    }

    /**
     * getAvailableTypes
     *
     * @return array
     */
    public function getAvailableTypes()
    {
        return $this->pageTypesAvailable;
    }

    /**
     * isTypeAvailable
     *
     * @param string $type
     *
     * @return bool
     */
    public function isTypeAvailable($type)
    {
        return array_key_exists($type, $this->pageTypesAvailable);
    }

    /**
     * NOTE: this delete method should be changed
     * getDeletedType
     *
     * @param string $type
     *
     * @return mixed
     */
    public function getDeletedType($type = self::NORMAL)
    {
        return str_replace(self::DELETED, $type, '{originalPageType}');
    }
}
