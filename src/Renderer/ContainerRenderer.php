<?php

namespace Rcm\Renderer;

use Rcm\Entity\Page;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Exception\PluginReturnedResponseException;
use Rcm\Service\PluginManager;
use Zend\View\Renderer\PhpRenderer;

/**
 * @todo FUTURE
 * Class ContainerRenderer
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ContainerRenderer
{
    /**
     * @var \Rcm\Service\PluginManager
     */
    protected $pluginManager;

    /**
     * @var Site
     */
    protected $currentSite;

    /**
     * @var  \Zend\Stdlib\ResponseInterface
     */
    protected $response;

    /**
     * @var string Default if nothing passed
     */
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
     * @param PhpRenderer $view
     *
     * @return Site
     */
    public function getSite(
        PhpRenderer $view
    ) {
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
     * @param PhpRenderer $view
     * @param string      $name       Container Name
     * @param integer     $revisionId Revision Id to Render
     *
     * @return null|string
     */
    public function renderContainer(
        PhpRenderer $view,
        $name,
        $revisionId = null
    ) {
        $site = $this->getSite($view);

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
                $pluginHtml = $this->getPluginRowsHtml($view, $pluginWrapperRows);
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
     * @param PhpRenderer $view
     * @param string      $name Container Name
     *
     * @return string
     */
    public function renderPageContainer(
        PhpRenderer $view,
        $name = ''
    ) {
        $name = $this->prepareContainerName($name);

        return $this->getPageContainerHtmlByName(
            $view,
            $view->page,
            $name
        );
    }

    /**
     * getPageContainerHtmlByName
     *
     * @param PhpRenderer $view
     * @param Page        $page
     * @param string      $name
     *
     * @return string
     */
    protected function getPageContainerHtmlByName(
        PhpRenderer $view,
        Page $page,
        $name
    ) {
        $revision = $page->getCurrentRevision();

        if (empty($revision)) {
            throw new PageNotFoundException('No revision found for this page.');
        }

        $pluginWrapperRows = $revision->getPluginWrappersByPageContainerName(
            $name
        );

        $pluginHtml = '';

        if (!empty($pluginWrapperRows) && is_array($pluginWrapperRows)) {
            $pluginHtml = $this->getPluginRowsHtml(
                $view,
                $pluginWrapperRows
            );
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

        $html = '<div class="container-fluid rcmContainer section-container"'
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
     * @param PhpRenderer $view
     * @param             $pluginWrapperRows
     *
     * @return string
     */
    protected function getPluginRowsHtml(
        PhpRenderer $view,
        $pluginWrapperRows
    ) {
        $html = '';
        foreach ($pluginWrapperRows as $pluginRow) {
            $html .= $this->getPluginRowHtml(
                $view,
                $pluginRow
            );
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
    protected function getPluginRowHtml(
        PhpRenderer $view,
        $pluginWrapperRow
    ) {
        $values = array_values($pluginWrapperRow);
        if (empty($values[0])) {
            return '';
        }

        $html = '<div class="row">';

        foreach ($pluginWrapperRow as $wrapper) {
            $html .= $this->getPluginHtml(
                $view,
                $wrapper
            );
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Get Plugin Html
     *
     * @param PhpRenderer   $view
     * @param PluginWrapper $pluginWrapper Plugin Wrapper
     *
     * @return string
     */
    protected function getPluginHtml(
        PhpRenderer $view,
        PluginWrapper $pluginWrapper
    ) {
        $this->pluginManager->prepPluginForDisplay(
            $pluginWrapper->getInstance()
        );

        $this->getPluginCss(
            $view,
            $pluginWrapper->getInstance()
        );
        $this->getPluginHeadScript(
            $view,
            $pluginWrapper->getInstance()
        );

        $plugin = $pluginWrapper->getInstance();

        $displayName = str_replace(' ', '', $plugin->getDisplayName());

        if ($displayName !== '') {
            $displayName = ' ' . $displayName;
        }

        $html
            = '<div class="rcmPlugin ' . $plugin->getPlugin() . $displayName
            . ' ' . $pluginWrapper->getColumnClass() . '"'
            . ' data-rcmPluginName="' . $plugin->getPlugin() . '"'
            . ' data-rcmPluginDefaultClass="rcmPlugin ' . $plugin->getPlugin()
            . $displayName . '"'
            . ' data-rcmPluginColumnClass="' . $pluginWrapper->getColumnClass()
            . '"'
            . ' data-rcmPluginRowNumber="' . $pluginWrapper->getRowNumber()
            . '"'
            . ' data-rcmPluginRenderOrderNumber="'
            . $pluginWrapper->getRenderOrderNumber() . '"'
            . ' data-rcmPluginInstanceId="' . $plugin->getInstanceId() . '"'
            . ' data-rcmPluginWrapperId="' . $pluginWrapper->getPluginWrapperId()
            . '"'
            . ' data-rcmSiteWidePlugin="' . $plugin->isSiteWide() . '"' //@deprecated <deprecated-site-wide-plugin>
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
     * @param PhpRenderer    $view
     * @param PluginInstance $instance
     *
     * @return void
     */
    protected function getPluginCss(
        PhpRenderer $view,
        PluginInstance $instance
    ) {
        $cssArray = $instance->getRenderedCss();

        if (!empty($cssArray)) {
            foreach ($cssArray as &$css) {
                $container = unserialize($css);

                if (!$this->isDuplicateCss($view, $container)) {
                    $view->headLink()->append($container);
                }
            }
        }
    }

    /**
     * getPluginHeadScript
     *
     * @param PhpRenderer    $view
     * @param PluginInstance $instance
     *
     * @return void
     */
    protected function getPluginHeadScript(
        PhpRenderer $view,
        PluginInstance $instance
    ) {
        $jsArray = $instance->getRenderedJs();

        if (!empty($jsArray)) {
            foreach ($jsArray as &$js) {
                $container = unserialize($js);

                if (!$this->isDuplicateScript($view, $container)) {
                    $view->headScript()->append($container);
                }
            }
        }
    }

    /**
     * Check to see if CSS is duplicated
     *
     * @param PhpRenderer                $view
     * @param \Zend\View\Helper\HeadLink $container Css Headlink
     *
     * @return bool
     */
    protected function isDuplicateCss(
        PhpRenderer $view,
        $container
    ) {
        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $view->headLink();

        $containers = $headLink->getContainer();

        foreach ($containers as &$item) {
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
     * @param PhpRenderer                  $view
     * @param \Zend\View\Helper\HeadScript $container
     *
     * @return bool
     */
    protected function isDuplicateScript(
        PhpRenderer $view,
        $container
    ) {
        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        $container = $headScript->getContainer();

        foreach ($container as &$item) {
            if (($item->source === null)
                && !empty($item->attributes['src'])
                && !empty($container->attributes['src'])
                && ($container->attributes['src'] == $item->attributes['src'])
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process plugins that return a response object.
     * This exception is thrown
     * when a plugin returns
     * a response object instead
     * of a ViewModel
     *
     * @param PluginReturnedResponseException $exception
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
