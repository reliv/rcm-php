<?php
/**
 * Rcm Container View Helper
 *
 * This file contains the class definition for the Rcm Container View Helper
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\View\Helper;

use Rcm\Entity\Page;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Exception\PluginReturnedResponseException;
use Rcm\Service\PluginManager;
use Zend\View\Helper\AbstractHelper;

/**
 * Rcm Container View Helper
 *
 * Rcm Container View Helper.  This helper will render plugin containers.  Use this
 * in your views to define a plugin container.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class Container extends AbstractHelper
{
    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    /** @var Site */
    protected $currentSite;

    /** @var  \Zend\Stdlib\ResponseInterface */
    protected $response;

    /** @var string Default if nothing passed */
    protected $defaultContainerName = 'body';

    /**
     * Constructor
     *
     * @param Site          $currentSite   Rcm Site
     * @param PluginManager $pluginManager Rcm Plugin Manager
     */
    public function __construct(
        Site $currentSite,
        PluginManager $pluginManager
    ) {
        $this->pluginManager = $pluginManager;
        $this->currentSite = $currentSite;
    }

    /**
     * Invoke Magic Method.  Required by AbstractHelper.
     *
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * getSite
     *
     * @return Site
     */
    public function getSite()
    {
        $view = $this->getView();

        /** @var \Rcm\Entity\Site $site */
        $site = $view->site;

        /** Fix for non CMS pages */
        if (empty($site)) {
            $site = $this->currentSite;
        }

        return $site;
    }

    /**
     * Render a plugin container
     *
     * @param string  $name       Container Name
     * @param integer $revisionId Revision Id to Render
     *
     * @return null|string
     */
    public function renderContainer($name, $revisionId = null)
    {
        $site = $this->getSite();

        $container = $site->getContainer($name);

        $pluginHtml = '';

        if (!empty($container)) {

            if (empty($revisionId)) {
                $revision = $container->getPublishedRevision();
            } else {
                $revision = $container->getRevisionById($revisionId);
            }

            $pluginWrapperRows = $revision->getPluginWrappersByRow();

            if (!empty($pluginWrapperRows)) {
                $pluginHtml = $this->getPluginRowsHtml($pluginWrapperRows);
            }

            $revisionId = $revision->getRevisionId();
        } else {
            $revisionId = -1;
        }

        // The front end demands rows in empty containers
        if (empty($pluginHtml)) {
            $pluginHtml .= '<div class="row"></div>';
        }

        return $this->getContainerWrapperHtml(
            $revisionId,
            $name,
            $pluginHtml,
            false
        );
    }

    /**
     * Render a Page Container
     *
     * @param string $name Container Name
     *
     * @return string
     */
    public function renderPageContainer($name = '')
    {
        $name = $this->prepareContainerName($name);

        /** @var \Zend\View\Renderer\PhpRenderer $view */
        $view = $this->getView();

        $view->headMeta($view->page->getDescription(), 'description');
        $view->headMeta($view->page->getKeywords(), 'keywords');
        $view->headTitle($view->page->getPageTitle());

        return $this->getPageContainerHtmlByName($view->page, $name);
    }

    /**
     * getPageContainerHtmlByName
     *
     * @param Page   $page
     * @param string $name
     *
     * @return string
     */
    protected function getPageContainerHtmlByName(Page $page, $name)
    {
        $revision = $page->getCurrentRevision();

        if (empty($revision)) {
            throw new PageNotFoundException('No revision found for this page.');
        }

        $pluginWrapperRows = $revision->getPluginWrappersByPageContainerName(
            $name
        );

        $pluginHtml = '';

        if (!empty($pluginWrapperRows) && is_array($pluginWrapperRows)) {

            $pluginHtml = $this->getPluginRowsHtml($pluginWrapperRows);
        }

        return $this->getContainerWrapperHtml(
            $revision->getRevisionId(),
            $name,
            $pluginHtml,
            true
        );
    }

    /**
     * getContainerWrapperHtml
     *
     * @param          $revisionId
     * @param string   $containerName
     * @param string   $pluginsHtml
     * @param bool     $pageContainer
     *
     * @return string
     */
    protected function getContainerWrapperHtml(
        $revisionId,
        $containerName,
        $pluginsHtml,
        $pageContainer = false
    ) {

        $html = '<div class="container-fluid rcmContainer"'
            . ' data-containerId="' . $containerName . '"'
            . ' data-containerRevision="'
            . $revisionId
            . '"';

        if ($pageContainer) {
            $html .= ' data-isPageContainer="Y"';
        }

        $html .= ' id="' . $containerName . '">';

        $html .= $pluginsHtml;

        $html .= '</div>';

        return $html;
    }

    /**
     * getPluginRowsHtml
     *
     * @param $pluginWrapperRows
     *
     * @return string
     */
    protected function getPluginRowsHtml($pluginWrapperRows)
    {
        $html = '';
        foreach ($pluginWrapperRows as $pluginRow) {
            $html .= $this->getPluginRowHtml($pluginRow);
        }

        return $html;
    }

    /**
     * getPluginRowHtml
     *
     * @param array $pluginWrapperRow
     *
     * @return string
     */
    protected function getPluginRowHtml($pluginWrapperRow)
    {
        $values = array_values($pluginWrapperRow);
        if (empty($values[0])) {
            return '';
        }

        $html = '<div class="row">';

        foreach ($pluginWrapperRow as $wrapper) {
            $html .= $this->getPluginHtml($wrapper);
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Get Plugin Html
     *
     * @param PluginWrapper $pluginWrapper Plugin Wrapper
     *
     * @return string
     */
    protected function getPluginHtml(PluginWrapper $pluginWrapper)
    {
        $this->pluginManager->prepPluginForDisplay(
            $pluginWrapper->getInstance()
        );
        $this->getPluginCss($pluginWrapper->getInstance());
        $this->getPluginHeadScript($pluginWrapper->getInstance());

        $plugin = $pluginWrapper->getInstance();

        $displayName = str_replace(' ', '', $plugin->getDisplayName());

        if($displayName !== ''){
            $displayName = ' ' . $displayName;
        }

        $html
            =
            '<div class="rcmPlugin ' . $plugin->getPlugin() . $displayName
            . ' ' . $pluginWrapper->getColumnClass() . '"'
            . ' data-rcmPluginName="' . $plugin->getPlugin() . '"'
            . ' data-rcmPluginDefaultClass="rcmPlugin ' . $plugin->getPlugin() . $displayName . '"'
            . ' data-rcmPluginColumnClass="' . $pluginWrapper->getColumnClass()
            . '"'
            . ' data-rcmPluginRowNumber="' . $pluginWrapper->getRowNumber()
            . '"'
            . ' data-rcmPluginRenderOrderNumber="'
            . $pluginWrapper->getRenderOrderNumber() . '"'
            . ' data-rcmPluginInstanceId="' . $plugin->getInstanceId() . '"'
            . ' data-rcmPluginWrapperId="' . $pluginWrapper->getPluginWrapperId(
            ) . '"'
            . ' data-rcmSiteWidePlugin="' . $plugin->isSiteWide() . '"'
            . ' data-rcmPluginDisplayName="' . $plugin->getDisplayName() . '"'
            . '>';

        $html .= '<div class="rcmPluginContainer">';

        $html .= $plugin->getRenderedHtml();

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * getPluginCss
     *
     * @param PluginInstance $instance
     *
     * @return void
     */
    protected function getPluginCss(PluginInstance $instance)
    {
        /** @var \Zend\View\Model\ViewModel $view */
        $view = $this->getView();

        $cssArray = $instance->getRenderedCss();

        if (!empty($cssArray)) {
            foreach ($cssArray as &$css) {
                $container = unserialize($css);

                if (!$this->isDuplicateCss($container)) {
                    $view->headLink()->append($container);
                }
            }
        }
    }

    /**
     * getPluginHeadScript
     *
     * @param PluginInstance $instance
     *
     * @return void
     */
    protected function getPluginHeadScript(PluginInstance $instance)
    {
        $view = $this->getView();

        $jsArray = $instance->getRenderedJs();

        if (!empty($jsArray)) {
            foreach ($jsArray as &$js) {
                $container = unserialize($js);

                if (!$this->isDuplicateScript($container)) {
                    $view->headScript()->append($container);
                }
            }
        }
    }

    /**
     * Check to see if CSS is duplicated
     *
     * @param \Zend\View\Helper\HeadLink $container Css Headlink
     *
     * @return bool
     */
    protected function isDuplicateCss($container)
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $view->headLink();

        foreach ($headLink->getContainer() as &$item) {
            if (($item->rel == 'stylesheet')
                && ($item->href == $container->href)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check to see if Scripts are duplicated
     *
     * @param \Zend\View\Helper\HeadScript $container Container to check
     *
     * @return bool
     */
    protected function isDuplicateScript($container)
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        foreach ($headScript->getContainer() as &$item) {
            if (($item->source === null)
                && array_key_exists('src', $item->attributes)
                && array_key_exists('src', $container->attributes)
                && ($container->attributes['src'] == $item->attributes['src'])
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process plugins that return a response object.
     *
     * @param PluginReturnedResponseException $exception This exception is thrown
     *                                                   when a plugin returns
     *                                                   a response object instead
     *                                                   of a ViewModel
     *
     * @return void
     */
    protected function handlePluginResponse(
        PluginReturnedResponseException $exception
    ) {
        $this->response = $exception->getResponse();
    }

    /**
     * Returns a previously stored response object
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * prepareConatinerName
     *
     * @param string $name
     *
     * @return string
     */
    protected function prepareContainerName($name = '')
    {
        $name = (string)$name;

        if (empty($name)) {

            return $this->defaultContainerName;
        }

        return $name;
    }
}
