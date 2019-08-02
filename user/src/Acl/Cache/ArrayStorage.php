<?php

namespace RcmUser\Acl\Cache;

use Zend\Cache\Storage\Adapter\AbstractAdapter;

/**
 * Class ArrayStorage
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Cache
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ArrayStorage extends AbstractAdapter
{
    protected $storageArray = [];

    /**
     * internalGetItem
     *
     * @param string $normalizedKey
     * @param null   $success
     * @param null   $casToken
     *
     * @return mixed
     */
    protected function internalGetItem(
        & $normalizedKey,
        & $success = null,
        & $casToken = null
    ) {
        if (array_key_exists($normalizedKey, $this->storageArray)) {
            return $this->storageArray[$normalizedKey];
        }

        return null;
    }

    /**
     * internalSetItem
     *
     * @param string $normalizedKey
     * @param mixed  $value
     *
     * @return void
     */
    protected function internalSetItem(& $normalizedKey, & $value)
    {
        $this->storageArray[$normalizedKey] = $value;
    }

    /**
     * internalRemoveItem
     *
     * @param string $normalizedKey
     *
     * @return void
     */
    protected function internalRemoveItem(& $normalizedKey)
    {
        unset($this->storageArray[$normalizedKey]);
    }
}
