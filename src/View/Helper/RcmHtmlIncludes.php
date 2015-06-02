<?php

namespace Rcm\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class RcmHtmlIncludes
 *
 * Include standard client html libraries
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmHtmlIncludes extends AbstractHelper
{
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
    protected function getMetaConfig()
    {
        return array_reverse($this->htmlIncludesConfig['headMetaName']);
    }

    /**
     * getScriptConfig
     *
     * @return array
     */
    protected function getScriptConfig()
    {
        return array_reverse($this->htmlIncludesConfig['headScriptFile']);
    }

    /**
     * getStylesheetsConfig
     *
     * @return array
     */
    protected function getStylesheetsConfig()
    {
        return array_reverse($this->htmlIncludesConfig['headLinkStylesheet']);
    }

    /**
     * getConfigValue
     *
     * @param array  $config
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function getConfigValue($config, $key, $default = null)
    {
        if (isset($config[$key])) {
            return $config[$key];
        }

        return $default;
    }

    /**
     * includeMeta
     *
     * @return void
     */
    protected function includeMeta()
    {
        $config = $this->getMetaConfig();

        if (empty($config)) {
            return;
        }

        $view = $this->getView();

        foreach ($config as $name => $options) {
            $content = $this->getConfigValue($options, 'content', '');
            $modifiers = $this->getConfigValue($options, 'modifiers', []);
            $view->headMeta()->prependName($name, $content, $modifiers);
        }
    }

    /**
     * includeScripts
     *
     * @return void
     */
    protected function includeScripts()
    {
        $config = $this->getScriptConfig();

        if (empty($config)) {
            return;
        }

        $view = $this->getView();
        $headScript = $view->headScript();

        foreach ($config as $src => $options) {
            $type = $this->getConfigValue($options, 'type', 'text/javascript');
            $attrs = $this->getConfigValue($options, 'attrs', []);
            $headScript->prependFile($src, $type, $attrs);
        }
    }

    /**
     * includeStylesheets
     *
     * @return void
     */
    protected function includeStylesheets()
    {
        $config = $this->getStylesheetsConfig();

        if (empty($config)) {
            return;
        }

        $view = $this->getView();
        $headLink = $view->headLink();

        foreach ($config as $href => $options) {
            $media = $this->getConfigValue($options, 'media', 'screen');
            $conditionalStylesheet = $this->getConfigValue($options, 'conditionalStylesheet', '');
            $extras = $this->getConfigValue($options, 'extras', []);
            $headLink->prependStylesheet($href, $media, $conditionalStylesheet, $extras);
        }
    }

    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke()
    {
        $this->includeMeta();
        $this->includeScripts();
        $this->includeStylesheets();

        return;
    }
}
