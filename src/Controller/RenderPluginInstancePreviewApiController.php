<?php

namespace Rcm\Controller;

use Rcm\Service\PluginManager;
use Zend\View\Model\JsonModel;

/**
 * This provides an API that allows plugins to ask the server to re-render them when they are edited
 * but not yet saved. JSON is returned rather than HTML to prevent XSS attacks.
 *
 * Class RenderPluginInstancePreviewApiController
 * @package Rcm\Controller
 */
class RenderPluginInstancePreviewApiController extends AbstractActionController
{
    protected $pluginManager;

    public function __construct(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }

    public function indexAction()
    {
        $pluginType = $this->params()->fromPost('pluginType');
        $instanceId = $this->params()->fromPost('instanceId');
        $instanceConfig = $this->params()->fromPost('instanceConfig');

        $viewData = $this->pluginManager->getPluginViewData(
            $pluginType,
            $instanceId,
            array_merge(
                $this->pluginManager->getDefaultInstanceConfig($pluginType),
                $instanceConfig
            )
        );

        $html = $viewData['html'];
        $responseData = ['html' => $html];

        return new JsonModel($responseData);
    }
}
