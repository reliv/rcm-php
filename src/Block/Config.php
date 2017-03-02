<?php

namespace Rcm\Block;

/**
 * @GammaRelease
 * Class Config
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
interface Config
{
    /**
     * get
     *
     * @param string     $key
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * getCategory
     *
     * @return string
     */
    public function getCategory();

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel();

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription();

    /**
     * getRenderer
     *
     * @return string
     */
    public function getRenderer();

    /**
     * getDataProvider
     *
     * @return string
     */
    public function getDataProvider();

    /**
     * getIcon (URL)
     *
     * @return string
     */
    public function getIcon();

    /**
     * getCache
     *
     * @return bool
     */
    public function getCache();

    /**
     * getFields
     *
     * @return array
     */
    public function getFields();

    /**
     * getDefaultConfig
     *
     * @return array
     */
    public function getDefaultConfig();
}
