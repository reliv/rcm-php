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
use Rcm\Entity\Revision;
use Rcm\Exception\PluginReturnedResponseException;
use Rcm\Service\ContainerManager;
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
    /** @var \Rcm\Service\ContainerManager */
    protected $containerManager;

    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    /** @var  \Zend\Stdlib\ResponseInterface */
    protected $response;

    /**
     * Constructor
     *
     * @param ContainerManager $containerManager Rcm Container Manager
     * @param PluginManager    $pluginManager    Rcm Plugin Manager
     */
    public function __construct(
        ContainerManager $containerManager,
        PluginManager $pluginManager
    ) {
        $this->containerManager = $containerManager;
        $this->pluginManager = $pluginManager;
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
     * Render a plugin container
     *
     * @param string  $name     Container Name
     * @param integer $revision Revision Id to Render
     *
     * @return null|string
     */
    public function renderContainer($name, $revision = null)
    {
        try {
            $containerData
                = $this->containerManager->getRevisionInfo($name, $revision);
        } catch (PluginReturnedResponseException $exception) {
            $this->handlePluginResponse($exception);

            return null;
        }

        $html = '';
        $html .= $this->getContainerHtml($containerData);

        return $html;
    }

    /**
     * Render a Page Container
     *
     * @param string $name Container Name
     *
     * @return string
     */
    public function renderPageContainer($name)
    {
        /** @var \Zend\View\Model\ViewModel $view */
        $view = $this->getView();

        return $this->getPageContainerHtmlByName($view->page, $name);
    }

    protected function getPageContainerHtmlByName(Page $page, $name)
    {
        $revision = $page->getCurrentRevision();
        $pluginWrappers = $revision->getPluginWrappersByPageContainerName($name);

        $pluginHtml = '';

        if (!empty($pluginWrappers) && is_array($pluginWrappers)) {
            foreach ($pluginWrappers as $wrapper) {
                $pluginHtml .= $this->getNewPluginHtml($wrapper);
            }
        }

        return $this->getContainerWrapperHtml($revision, $name, $pluginHtml, true);
    }

    protected function getContainerWrapperHtml(
        Revision $revision,
        $containerName,
        $pluginsHtml,
        $pageContainer = false
    ) {

        $html = '<div class="rcmContainer"'
            . ' data-containerId="' . $containerName . '"'
            . ' data-containerRevision="'
            . $revision->getRevisionId()
            . '"';

        if ($pageContainer) {
            $html .= ' data-isPageContainer="Y"';
        }

        $html .= ' id="' . $containerName . '">';

        $html .= $pluginsHtml;

        $html .= '<div style="clear:both;"></div></div>';

        return $html;
    }

    /**
     * Get Container HTML
     *
     * @param array  $containerData     container db result
     * @param string $pageContainerName Page container name for page containers.
     *
     * @return string
     * @deprecated
     */
    protected function getContainerHtml(
        $containerData,
        $pageContainerName = null
    ) {
        $containerName = $containerData['name'];
        $isPageContainer = false;

        if (!empty($pageContainerName)) {
            $containerName = $pageContainerName;
            $isPageContainer = true;
        }

        $html = '<div class="rcmContainer"'
            . ' data-containerId="' . $containerName . '"'
            . ' data-containerRevision="'
            . $containerData['revision']['revisionId']
            . '"';

        if ($isPageContainer) {
            $html .= ' data-isPageContainer="Y"';
        }

        $html .= ' id="' . $containerData['name'] . '">';

        foreach ($containerData['revision']['pluginWrappers'] as &$pluginInstance) {
            if ($isPageContainer
                && $pluginInstance['layoutContainer'] != $pageContainerName
            ) {
                continue;
            }

            $html .= $this->getPluginHtml($pluginInstance);
            $this->getPluginCss($pluginInstance);
            $this->getPluginHeadScript($pluginInstance);
        }

        $html .= '<div style="clear:both;"></div></div>';

        return $html;
    }

    /**
     * Get Plugin Html
     *
     * @param PluginWrapper $pluginWrapper Plugin Wrapper
     *
     * @return string
     */
    protected function getNewPluginHtml(PluginWrapper $pluginWrapper)
    {
        $extraStyle = '';
        $resized = 'N';

        $this->pluginManager->prepPluginForDisplay($pluginWrapper->getInstance());
        $this->getNewPluginCss($pluginWrapper->getInstance());
        $this->getNewPluginHeadScript($pluginWrapper->getInstance());

        if (!empty($pluginWrapper->getHeight())) {
            $extraStyle .= 'height: ' . $pluginWrapper->getHeight() . '; ';
            $resized = 'Y';
        }

        if (!empty($pluginWrapper->getWidth())) {
            $extraStyle .= 'width: ' . $pluginWrapper->getWidth() . '; ';
            $resized = 'Y';
        }

        $extraStyle .= 'float: left; ';

        if (!empty($pluginWrapper->getDivFloat())) {
            $extraStyle .= 'float: ' . $pluginWrapper->getDivFloat() . '; ';
        }

        $plugin = $pluginWrapper->getInstance();

        $html = '<div class="rcmPlugin '
            . $plugin->getPlugin() . ' '
            . str_replace(' ', '', $plugin->getDisplayName())
            . ' "'
            . ' data-rcmPluginName="' . $plugin->getPlugin() . '"'
            . ' data-rcmPluginInstanceId="'
            . $plugin->getInstanceId()
            . '"'
            . ' data-rcmSiteWidePlugin="' . $plugin->isSiteWide()
            . '"'
            . ' data-rcmPluginResized="' . $resized . '"'
            . ' data-rcmPluginDisplayName="'
            . $plugin->getDisplayName()
            . '"'
            . ' style=" ' . $extraStyle
            . '">';

        $html .= '<div class="rcmPluginContainer">';

        $html .= $plugin->getRenderedHtml();

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Get Plugin Html
     *
     * @param array &$pluginData Plugin Data
     *
     * @return string
     */
    protected function getPluginHtml(&$pluginData)
    {
        $extraStyle = '';
        $resized = 'N';

        if (!empty($pluginData['height'])) {
            $extraStyle .= 'height: ' . $pluginData['height'] . 'px; ';
            $resized = 'Y';
        }

        if (!empty($pluginData['width'])) {
            $extraStyle .= 'width: ' . $pluginData['width'] . 'px; ';
            $resized = 'Y';
        }

        $extraStyle .= 'float: left; ';

        if (!empty($pluginData['divFloat'])) {
            $extraStyle .= 'float: ' . $pluginData['divFloat'] . '; ';
        }

        $html = '<div class="rcmPlugin '
            . $pluginData['instance']['plugin'] . ' '
            . str_replace(' ', '', $pluginData['instance']['displayName'])
            . ' "'
            . ' data-rcmPluginName="' . $pluginData['instance']['plugin'] . '"'
            . ' data-rcmPluginInstanceId="'
            . $pluginData['instance']['pluginInstanceId']
            . '"'
            . ' data-rcmSiteWidePlugin="' . $pluginData['instance']['siteWide']
            . '"'
            . ' data-rcmPluginResized="' . $resized . '"'
            . ' data-rcmPluginDisplayName="'
            . $pluginData['instance']['displayName']
            . '"'
            . ' style=" ' . $extraStyle
            . '">';

        $html .= '<div class="rcmPluginContainer">';

        $html .= $pluginData['instance']['renderedData']['html'];

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    protected function getNewPluginCss(PluginInstance $instance)
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

    protected function getNewPluginHeadScript(PluginInstance $instance)
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
     * Get Plugin CSS
     *
     * @param array &$pluginData Plugin Data
     *
     * @return void
     */
    protected function getPluginCss(&$pluginData)
    {
        /** @var \Zend\View\Model\ViewModel $view */
        $view = $this->getView();

        if (!empty($pluginData['instance']['renderedData']['css'])) {
            foreach ($pluginData['instance']['renderedData']['css'] as &$css) {
                $container = unserialize($css);

                if (!$this->isDuplicateCss($container)) {
                    $view->headLink()->append($container);
                }
            }
        }
    }

    /**
     * Get Plugin Head Script
     *
     * @param array &$pluginData Plugin data
     *
     * @return void
     */
    protected function getPluginHeadScript(&$pluginData)
    {
        $view = $this->getView();

        if (!empty($pluginData['instance']['renderedData']['js'])) {
            foreach ($pluginData['instance']['renderedData']['js'] as &$js) {
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
}
