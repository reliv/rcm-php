<?php
/**
 * Get content base path helper
 *
 * Gets the content base path with content symlinks
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

namespace Rcm\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Create a layout container in your page layouts.
 *
 * This is a view helper to render out page containers inside page layouts.
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class GetContentBasePath extends AbstractHelper
{

    public function __invoke()
    {
        return $this->getContentBasePath();
    }


    public function getContentBasePath()
    {
//        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
//        $renderer = $this->getView();
//
//        /** @var \Zend\View\Helper\ViewModel $helper  */
//        $helper = $renderer->plugin('view_model');
//
//        $view = $helper->getCurrent();
//
//        $instanceId = $view->getVariable('rcmPluginInstanceId');
//        $adminMode = $view->getVariable('rcmAdminMode');
//
//        if($adminMode){
        return '';
//        }else{
//            // TODO REMOVE HARD CODED PATH
//            return '/published-content' . '/' .$instanceId;
//        }

    }
}