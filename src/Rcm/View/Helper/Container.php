<?php

namespace Rcm\View\Helper;

use Rcm\Service\ContainerManager;
use Zend\View\Helper\AbstractHelper;

class Container extends AbstractHelper
{
    protected $containerManager;

    public function __construct(ContainerManager $containerManager) {
        $this->containerManager = $containerManager;
    }

    public function __invoke()
    {
        return $this;
    }

    public function renderContainer($name)
    {
        $containerData = $this->containerManager->getContainerByName($name);


        $html = '';
        $html .= $this->getContainerHtml($containerData);

        return $html;
    }

    public function renderPageContainer($name)
    {
        $view = $this->getView();

        $html = '';
        $html .= $this->getContainerHtml($view->pageInfo, $name);

        return $html;

    }

    public function getContainerHtml($containerData, $pageContainerName=null)
    {
        if (!empty($pageContainerName)) {
            $containerName = $pageContainerName;
            $isPageContainer = true;
        } else {
            $containerName = $containerData['name'];
            $isPageContainer = false;
        }

        $html = '<div class="rcmContainer"'
                .' data-containerId="'.$containerName.'"'
                .' data-containerRevision="'.$containerData['revision']['revisionId'].'"';

        if ($isPageContainer) {
            $html .= ' data-isPageContainer="Y"';
        }

        $html .= ' id="'.$containerData['name'].'">';

        foreach ($containerData['revision']['pluginInstances'] as &$pluginInstance) {

            if ($isPageContainer && $pluginInstance['layoutContainer'] != $pageContainerName) {
                continue;
            }

            $html .= $this->getPluginHtml($pluginInstance, $pageContainerName);
        }

        $html .= '</div>';

        return $html;
    }

    public function getPluginHtml(&$pluginData)
    {
        $extraStyle = '';
        $resized = 'N';

        $view = $this->getView();

        if (!empty($pluginData['instance']['renderedData']['css'])) {
            foreach ($pluginData['instance']['renderedData']['css'] as $css) {
                $container = unserialize($css);

                if (!$this->isDuplicateCss($container)) {
                    $view->headLink()->append($container);
                }
            }
        }

        if (!empty($pluginData['instance']['renderedData']['js'])) {
            foreach ($pluginData['instance']['renderedData']['js'] as $js) {
                $container = unserialize($js);

                if (!$this->isDuplicateScript($container)) {
                    $view->headScript()->append($container);
                }
            }
        }


        if (!empty($pluginData['height'])) {
            $extraStyle .= 'height: '.$pluginData['height'].'px; ';
            $resized='Y';
        }

        if (!empty($pluginData['width'])) {
            $extraStyle .= 'width: '.$pluginData['width'].'px; ';
            $resized='Y';
        }

        if (!empty($pluginData['divFloat'])) {
            $extraStyle .= 'float: '.$pluginData['divFloat'].'; ';
        } else {
            $extraStyle .= 'float: left; ';
        }


        $html = '<div class="rcmPlugin '.$pluginData['instance']['plugin'].' '.str_replace(' ', '', $pluginData['instance']['displayName']).' "'
                .' data-rcmPluginName="'.$pluginData['instance']['plugin'].'"'
                .' data-rcmPluginInstanceId="'.$pluginData['instance']['pluginInstanceId'].'"'
                .' data-rcmSiteWidePlugin="'.$pluginData['instance']['siteWide'].'"'
                .' data-rcmPluginResized="'.$resized.'"'
                .' data-rcmPluginDisplayName="'.$pluginData['instance']['displayName'].'"'
                .' style=" '.$extraStyle.'">';

        $html .= '<div class="rcmPluginContainer">';

        $html .= $pluginData['instance']['renderedData']['html'];

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    protected function isDuplicateCss($container)
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $view->headLink();

        foreach ($headLink->getContainer() as $item) {
            if (($item->rel == 'stylesheet') && ($item->href == $container->href)) {
                return true;
            }
        }

        return false;
    }

    protected function isDuplicateScript($container)
    {
        $view = $this->getView();

        /** @var \Zend\View\Helper\HeadScript $headScript */
        $headScript = $view->headScript();

        foreach ($headScript->getContainer() as $item) {
            if (($item->source === null)
                && array_key_exists('src', $item->attributes)
                && ($container->attributes['src'] == $item->attributes['src']))
            {
                return true;
            }
        }

        return false;
    }
}