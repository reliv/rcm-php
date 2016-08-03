<?php

namespace Rcm\Service;

/**
 * Class HtmlIncludes
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class HtmlIncludes
{
    /**
     * @var array
     */
    protected $htmlIncludesConfig;

    /**
     * @param $htmlIncludesConfig
     */
    public function __construct($htmlIncludesConfig)
    {
        $this->htmlIncludesConfig = $htmlIncludesConfig;
    }

    /**
     * getMetaConfig
     *
     * @return array
     */
    public function getMetaConfig()
    {
        return $this->htmlIncludesConfig['headMetaName'];
    }

    /**
     * getScriptConfig
     *
     * @param $scriptKey
     *
     * @return array
     */
    public function getScriptConfig($scriptKey)
    {
        $includes = [];

        foreach ($this->htmlIncludesConfig['sections'] as $section) {
            $includes = $includes + $this->htmlIncludesConfig[$scriptKey][$section];
            if ($section == 'libraries') {
                /* @deprecated Using 'headScriptFile' first to revent BC breaks */
                $includes = $includes + $this->htmlIncludesConfig['headScriptFile'];
            }
        }

        return $includes;
    }

    /**
     * getDefaultScriptConfig
     *
     * @return array
     */
    public function getDefaultScriptConfig()
    {
        return $this->getScriptConfig($this->htmlIncludesConfig['defaultScriptKey']);
    }

    /**
     * getStylesheetsConfig
     *
     * @param $stylesheetKey
     *
     * @return array
     */
    public function getStylesheetsConfig($stylesheetKey)
    {
        $includes = [];

        foreach ($this->htmlIncludesConfig['sections'] as $section) {
            $includes = $includes + $this->htmlIncludesConfig[$stylesheetKey][$section];
            if ($section == 'libraries') {
                /* @deprecated Using 'headLinkStylesheet' first to revent BC breaks */
                $includes = $includes + $this->htmlIncludesConfig['headLinkStylesheet'];
            }
        }

        return $includes;
    }

    /**
     * getDefaultStylesheetsConfig
     *
     * @return array
     */
    public function getDefaultStylesheetsConfig()
    {
        return $this->getStylesheetsConfig($this->htmlIncludesConfig['defaultStylesheetKey']);
    }
}
