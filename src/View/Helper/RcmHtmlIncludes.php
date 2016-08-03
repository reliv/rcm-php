<?php

namespace Rcm\View\Helper;

use Rcm\Service\HtmlIncludes;
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
     * @var bool
     */
    protected $done = false;

    /**
     * @var HtmlIncludes
     */
    protected $htmlIncludesService;

    /**
     * RcmHtmlIncludes constructor.
     *
     * @param HtmlIncludes $htmlIncludesService
     */
    public function __construct(HtmlIncludes $htmlIncludesService)
    {
        $this->htmlIncludesService = $htmlIncludesService;
    }

    /**
     * getMetaConfig
     *
     * @return array
     */
    protected function getMetaConfig()
    {
        $includes = $this->htmlIncludesService->getMetaConfig();

        return array_reverse($includes);
    }

    /**
     * getScriptConfig
     *
     * @return array
     */
    protected function getScriptConfig()
    {
        $includes = $this->htmlIncludesService->getDefaultScriptConfig();

        return array_reverse($includes);
    }

    /**
     * getStylesheetsConfig
     *
     * @return array
     */
    protected function getStylesheetsConfig()
    {
        $includes = $this->htmlIncludesService->getDefaultStylesheetsConfig();

        return array_reverse($includes);
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
            $conditionalStylesheet = $this->getConfigValue(
                $options,
                'conditionalStylesheet',
                ''
            );
            $extras = $this->getConfigValue($options, 'extras', []);
            $headLink->prependStylesheet(
                $href,
                $media,
                $conditionalStylesheet,
                $extras
            );
        }
    }

    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke()
    {
        /* this should only be done once per request */
        if ($this->done) {
            return;
        }
        $this->includeMeta();
        $this->includeScripts();
        $this->includeStylesheets();

        $this->done = true;

        return;
    }
}
