<?php
/**
 * Add Layout Editor Plugin Containers Helper.
 *
 * Contains the view helper to add a layout container to a page layout
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

namespace Rcm\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Add Layout Editor Plugin Containers Helper.
 *
 * This is a view helper to render out page containers inside page layouts.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class RenderLayoutEditorContainers extends AbstractHelper
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
    public function __invoke($containers)
    {
        return $this->getLayoutEditorContainers($containers);
    }


    public function getLayoutEditorContainers($containers)
    {
        if (empty($containers) || !is_array($containers)) {
            return null;
        }

        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->getView();
        $basePath = $renderer->basePath();

        /** @var \Zend\View\Helper\ViewModel $helper */
        $helper = $renderer->plugin('view_model');
        $view = $helper->getCurrent();

        $html = '';

        $html .= '<div id="rcmLayoutHandlerBar">';
        $html .= '<div id="rcmLayoutMenuClose"></div>';
        $html .= '<div id="rcmLayoutMenuPopout"></div>';
        $html .= '<div id="rcmLayoutMenuMinimize"></div>';
        $html .= '<div align="center">Available Plugins Menu</div>';
        $html .= '</div>';
        $html .= '<div id="rcmLayoutAccordion">';

        foreach ($containers as $containerName => $containerContents) {
            $html .= '<h3><a href="#">' . $containerName . '</a></h3>';
            $html .= '<div>';

            /** @var \Rcm\Entity\PluginInstance $plugin */
            foreach ($containerContents as $pluginName => $plugin) {
                if (empty($plugin)) {
                    continue;
                }

                //Add Plugin Css
                if ($plugin->hasAdminCss()) {
                    $renderer->headLink()->appendStylesheet(
                        $basePath . $plugin->getAdminEditCss()
                    );
                }

                $html .= $this->addItem($plugin);
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    public function addItem(\Rcm\Entity\PluginInstance $plugin)
    {
        $html = '';
        $renderer = $this->getView();

        $html .= '<div class="rcmPluginDrag">';

        $html .= '<div class="layoutPluginContainerIcon" title="'
            . $renderer->escapeHtml($plugin->getTooltip()) . '" >';

        $iconSrc = $plugin->getIcon();

        $html .= '<div class="rcmLayoutImage" >';
        if (!empty($iconSrc)) {
            $html
                .= '<img src="' . $iconSrc . '" alt="" width="40" height="40" />';
        } else {
            $html .= '<img src="/images/GenericIcon.png" width="40" height="40" alt="" />';
        }
        $html .= '</div>';

        $html .= '<div class="rcmLayoutTitle" >';
        $html .= $plugin->getDisplayName();
        $html .= '</div>';

        $html .= '</div>';
        $html .= '<div class="initialState" style="position: absolute; left: -99999px; display: none;" align="center">';
        $html .= $renderer->renderPlugin($plugin, null, null, null, false);
        $html .= '</div>';
        $html .= '<div style="clear: both;"></div>';
        $html .= '</div>';


        if ($plugin->isSiteWide()) {
            if ($plugin->getOnPage()) {
                $style = 'style="display: none;"';
            } else {
                $style = '';
            }

            $html
                = '<div id="' . str_replace(' ', '-', $plugin->getDisplayName())
                . '" ' . $style . '>' . $html . '</div>';
        }

        return $html;
    }
}