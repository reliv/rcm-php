<?php

namespace Rcm\Controller;

use Rcm\Service\PluginManager;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

/**
 * This provides an API that allows plugins to ask the server to re-render them when they are edited
 * but not yet saved. JSON is returned rather than HTML to prevent XSS attacks.
 *
 * Class RenderPluginInstancePreviewApiController
 *
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
        $data = json_decode(
            $this->getRequest()->getContent(),
            true
        );

        if (json_last_error() !== JSON_ERROR_NONE
            || !is_array($data)
            || !array_key_exists('pluginType', $data)
            || !array_key_exists('instanceId', $data)
            || !array_key_exists('instanceConfig', $data)
        ) {
            $response = new Response();
            $response->setContent(
                '400 Bad Request - Request body must be a JSON object'
                . ' that contains properties pluginType, instanceId, and instanceConfig'
            );
            $response->setStatusCode(400);

            return $response;
        }

        $viewData = $this->pluginManager->getPluginViewData(
            $data['pluginType'],
            $data['instanceId'],
            $data['instanceConfig']
        );

        $html = $viewData['html'];
        $responseData = ['html' => $html];

        return new JsonModel($responseData);
    }
}
