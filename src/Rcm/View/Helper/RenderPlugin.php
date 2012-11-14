<?php
/**
 * Render Plugin
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\View\Helper
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

namespace Rcm\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Create a layout container in your page layouts.
 *
 * This is a view helper to render out page containers inside page layouts.
 *
 * @category  Reliv
 * @package   Common\View\Helper
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 */
class RenderPlugin extends AbstractHelper
{
    /**
     * Function called when using $this->view->addLayoutContainer().  Will
     * call method renderLayoutContainer.  See method renderLayoutContainer
     * for more info
     *
     * @param array $containerNum Container Number to Render
     *
     * @return string Rendered HTML from plugins for the container specified
     */
    public function __invoke(\Rcm\Entity\PluginInstance $plugin, $height=null, $width=null, $float=null, $renderView=true)
    {
        return $this->renderPlugin($plugin, $height, $width, $float, $renderView);
    }

    protected function renderPlugin(\Rcm\Entity\PluginInstance $plugin, $height=null, $width=null, $float=null, $renderView=true)
    {
        $pluginInstanceId = $plugin->getInstanceId();
        $pluginName = $plugin->getName();

        $html = '<div class="rcmPlugin ';
        $html .= $pluginName.' ';

        if ($plugin->isSiteWide()) {
            $html .= str_replace(' ', '', $plugin->getDisplayName()). ' ';
        }

        $html .= '" ';
        $html .= 'data-rcmPluginName="'.$pluginName.'" ';
        $html .= 'data-rcmPluginInstanceId="'.$pluginInstanceId.'" ';

        if ($plugin->isSiteWide()) {
            $html .= 'data-rcmSiteWidePlugin="Y" ';
        } else {
            $html .= 'data-rcmSiteWidePlugin="N" ';

        }

        $html .= 'data-rcmPluginDisplayName="'.$plugin->getDisplayName().'" ';

        $html .= 'style="';

        if (!empty($width)) {
            $html .= " width: ".$width.";";
        }

        if (!empty($height)) {
            $html .= " height: ".$height.";";
        }

        if (!empty($float)) {
            $html .= " float: ".$float.";";
        }

        $html .= '">';

        $html .= '<div class="rcmPluginContainer">';

        if ($renderView === true) {
            $pluginView = $plugin->getView();

            $pluginView->setVariable(
                'rcmPluginInstanceId',
                $plugin->getInstanceId()
            );

            $html .= $this->getView()->render($pluginView);
        }

        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }
}