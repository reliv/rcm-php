<?php

namespace Rcm\Block\Config;

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
     * getDirectory
     *
     * @return string
     */
    public function getDirectory();

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
     * getEditor
     *
     * @return string
     */
    public function getEditor();

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

    /**
     * @return array
     */
    public function getConfigJsonWhitelist();

    /**
     * toArray
     *
     * @return array
     */
    public function toArray();
}
