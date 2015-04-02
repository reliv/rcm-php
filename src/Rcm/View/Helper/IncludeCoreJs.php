<?php

namespace Rcm\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class IncludeCoreJs
 *
 * Include standard JS libraries
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
class IncludeCoreJs extends AbstractHelper
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

        //Plugins inject their js into this file
        $headScript()->prependFile(
            $view->basePath() . '/modules/rcm/plugins.js'
        );

        //Plugins inject their css into this file
        $view->headLink()->prependStylesheet(
            $view->basePath() . '/modules/rcm/plugins.css'
        );

        /* <CORE_JS_FILES> */
        $headScript()->prependFile(
            $view->basePath() . '/modules/rcm/rcm-core.js'
        );
        $headScript()->prependFile(
            $view->basePath()
            . '/modules/rcm-angular-js/ocLazyLoad/dist/ocLazyLoad.js'
        );
        /* <TwitterBootstrap> */
        $view->headLink()->prependStylesheet(
            $view->basePath()
            . '/modules/rcm-twitter-bootstrap/bootstrap/css/bootstrap.css'
        );

        $headScript()->prependFile(
            $view->basePath()
            . '/modules/rcm-twitter-bootstrap/bootstrap/js/bootstrap.js'
        );

        $headScript()->appendFile(
            $view->basePath()
            . '/modules/rcm-twitter-bootstrap/bootbox/bootbox.min.js'
        );
        /* <TwitterBootstrap> */
        $headScript()->prependFile(
            $view->basePath() . '/modules/rcm-angular-js/angular/angular.js'
        );

        $headScript()->prependFile(
            $view->basePath()
            . '/modules/rcm-jquery/jquery-ui-1.10.4.custom/js/jquery-1.10.2.js'
        );

        $headScript()->prependFile(
            $view->basePath() . '/modules/rcm/es5-shim-master/es5-shim.min.js',
            'text/javascript',
            array('conditional' => 'lt IE 9')
        );
        /* </CORE_JS_FILES> */
    }
}
