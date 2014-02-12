<?php
/**
 * Add Layout Container Helper.
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
 */

namespace Rcm\View\Helper;

use \Zend\View\Helper\AbstractHelper;

/**
 * Create a layout container in your page layouts.
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
class GetLayoutContainer extends AbstractHelper
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
    public function __invoke($containerNum)
    {
        return $this->renderLayoutContainer($containerNum);
    }

    /**
     * Will render all plugins for the container passed to it.  For instance if
     * I want to render container number two in my page layout I would call
     * echo $this->view->addLayoutContainer(2);
     * Note: This object expects or assumes that the view or page layout has
     * an array of plugin view objects to render.
     *
     * @param array $containerNum Container Number to Render
     *
     * @return string Rendered HTML from plugins for the container specified
     */
    public function renderLayoutContainer($containerNum)
    {
        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->getView();

        if (!isset($renderer->renderedContainers)) {
            throw new \Exception('Content Manager Not Initialized.  Please make sure that you run RcmViewInit in your layout before calling this.');
        }

        $html = '<div class="rcmContainer" data-containerId="' . $containerNum
            . '" id="rcmContainer_' . $containerNum . '">';

        if (!empty($renderer->renderedContainers[$containerNum])
            && is_array($renderer->renderedContainers[$containerNum])
        ) {
            foreach (
                $renderer->renderedContainers[$containerNum] as $pluginHtml
            ) {
                $html .= $pluginHtml;
            }
        }

        $html .= '<div style="clear:both;"></div>';
        $html .= '</div>';

        return $html;
    }
}