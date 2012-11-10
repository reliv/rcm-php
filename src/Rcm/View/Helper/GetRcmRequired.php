<?php
/**
 * Get the required html/js/css for the content manager.
 *
 * Contains the view helper to add he required html/js/css for the content manager.  Must be included in all
 * layouts for the CMS to work correctly.
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
 * Get the required html/js/css for the content manager.
 *
 * Contains the view helper to add he required html/js/css for the content manager.  Must be included in all
 * layouts for the CMS to work correctly.
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
class GetRcmRequired extends AbstractHelper
{
    /**
     * @var string Internal storage to return HTML that needs added to the page.
     */
    private $html;

    /**
     * Function called when using $this->view->getRcmRequired().  Will
     * call method getRequired.  See method getRequired
     * for more info
     *
     * @return string Rendered HTML from plugins for the container specified
     */
    public function __invoke()
    {
        return $this->getRequired();
    }

    public function getRequired()
    {
        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->getView();

        $this->setHead($renderer);
    }

    protected function getRequiredAdmin(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $this->setAdminHead($renderer);
    }

    protected function setHead(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $this->setMeta($renderer);
        $this->setJs($renderer);
        $this->setCss($renderer);
    }

    protected function setMeta(\Zend\View\Renderer\PhpRenderer $renderer)
    {

        $renderer->headMeta()->appendName('description', $renderer->metaDesc);

        $renderer->headMeta()->appendName('keywords', $renderer->metaKeys);
    }

    protected function setJs(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        // HTML5 shim, for IE6-8 support of HTML elements
        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/js/html5.js', 'text/javascript',
            array('conditional' => 'lt IE 9',)
        );

        $renderer->headScript()->prependFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery-block-ui/jquery.blockUI.js', 'text/javascript'
        );
        $renderer->headScript()->prependFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery/js/jquery-ui-1.8.24.custom.min.js', 'text/javascript'
        );

        $renderer->headScript()->prependFile(
            $renderer->basePath() . '/modules/rcm/vendor/jquery/js/jquery-1.8.2.min.js', 'text/javascript'
        );
    }

    protected function setCss(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $renderer->headLink()->appendStylesheet(
            $renderer->basePath() . '/modules/rcm/vendor/jquery/css/smoothness/jquery-ui-1.8.21.custom.css'
        );
    }

    protected function setAdminHead(\Zend\View\Renderer\PhpRenderer $renderer)
    {
        $this->setAdminMeta($renderer);
        $this->setAdminJs($renderer);
        $this->setAdminCss($renderer);
    }

    protected function setAdminMeta(\Zend\View\Renderer\PhpRenderer $renderer)
    {

    }

    protected function setAdminJs(\Zend\View\Renderer\PhpRenderer $renderer)
    {

    }

    protected function setAdminCss(\Zend\View\Renderer\PhpRenderer $renderer)
    {

    }


}