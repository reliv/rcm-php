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

        if (json_last_error() !== JSON_ERROR_NONE) {
            $response = new Response();
            $response->setContent('400 Bad Request - Request body contains invalid json');
            $response->setStatusCode(400);

            return $response;
        }

        $error = $this->getValidationMessage($data);

        if (!empty($error)) {
            $response = new Response();
            $response->setContent('400 Bad Request - Request data failed validation');
            $response->setStatusCode(400);
            $response->setReasonPhrase($error);

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

    /**
     * @param $data
     *
     * @return string
     */
    protected function getValidationMessage($data)
    {
        $error = '';

        if (!is_array($data)) {
            $error .= 'Data must be object';

            return $error;
        }

        if (!array_key_exists('pluginType', $data)) {
            $error .= 'Data must be object';
        }

        if (!array_key_exists('pluginType', $data)) {
            $error .= 'Data must be object';
        }

        if (!array_key_exists('pluginType', $data)) {
            $error .= 'Data must be object';
        }

        return $error;
    }
}
