<?php

namespace Rcm\ConsoleController;

use Rcm\Controller\AbstractActionController;
use Rcm\Service\HtmlIncludes;
use Zend\Http\Response;

/**
 * The purpose of this controller is to return lists of "include"
 * files so that a grunt-type build can call this controller on the
 * CLI and build a combined file.
 *
 * Class ListHtmlIncludesController
 * @package Rcm\ConsoleController
 */
class ListHtmlIncludesController extends AbstractActionController
{
    protected $htmlIncludesService;

    public function __construct(HtmlIncludes $htmlIncludesService)
    {
        $this->htmlIncludesService = $htmlIncludesService;
    }

    /**
     * @return \Zend\Stdlib\Message
     *
     * @TODO Deal with things like "conditional": "lt IE 9" better. Right now
     * these just get included.
     */
    public function listJsAction()
    {
        $responseData = array_keys(
            $this->htmlIncludesService->getDefaultScriptConfig()
        );

        return $this->getJsonResponse($responseData);
    }

    /**
     * @return \Zend\Stdlib\Message
     *
     * @TODO Deal with things like "conditional": "lt IE 9" better. Right now
     * these just get included.
     */
    public function listCssAction()
    {
        $responseData = array_keys(
            $this->htmlIncludesService->getDefaultStylesheetsConfig()
        );

        return $this->getJsonResponse($responseData);
    }

    protected function getJsonResponse($responseData)
    {
        return (new Response())->setContent(
            json_encode($responseData, JSON_PRETTY_PRINT)
        );
    }

    protected function getHtmlIncludesConfig()
    {
        return $this->config['Rcm']['HtmlIncludes'];
    }
}
