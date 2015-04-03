<?php

namespace Rcm\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class IncludeHtmlEditor
 *
 * HtmlEditor View helper
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
class IncludeHtmlEditor extends AbstractHelper
{
    /**
     * __invoke
     *
     * @return void
     */
    public function __invoke()
    {
        $this->inject();

        return;
    }

    /**
     * inject
     *
     * @return void
     */
    protected function inject()
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        $headScript->appendFile(
            $view->basePath() . '/modules/rcm/rcm-html-editor.js'
        );

        $view->headLink()->appendStylesheet(
            $this->basePath() . '/modules/rcm/rcm-html-editor.css',
            'screen,print'
        );
    }
}
