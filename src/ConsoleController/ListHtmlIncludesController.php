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
    protected $includes;

    /**
     * This controls what configs this controller should read.
     * This controller cannot simply read the default keys because
     * they will be overwritten with the combined file built by grunt
     */
    protected $config = [
        'scriptsKey' => 'scripts',
        'styleSheetsKey' => 'stylesheets'
    ];

    public function __construct(HtmlIncludes $includes)
    {
        $this->includes = $includes;
    }

    /**
     * @TODO Deal with things like "conditional": "lt IE 9" better. Right now
     * these just get included.
     *
     * @return \Zend\Stdlib\Message
     * @throws \Exception
     */
    public function listScriptsAction()
    {
        $responseData = array_keys(
            $this->includes->getScriptConfig($this->config['scriptsKey'])
        );

//        foreach ($responseData as $file) {
//            $file = 'public' . $file;
//            if (!file_exists($file)) {
//                throw new \Exception(
//                    'The following file is not on the file system. '
//                    . 'You must warm the asset manager cache before running this. File: '
//                    . $file
//                );
//            }
//        }

        return $this->getJsonResponse($responseData);
    }

    /**
     * @TODO Deal with things like "conditional": "lt IE 9" better. Right now
     * these just get included.
     *
     * @return \Zend\Stdlib\Message
     * @throws \Exception
     */
    public function listStylesheetsAction()
    {
        $responseData = array_keys(
            $this->includes->getScriptConfig($this->config['styleSheetsKey'])
        );

//        foreach ($responseData as $file) {
//            $file = 'public' . $file;
//            if (!file_exists($file)) {
//                throw new \Exception(
//                    'The following file is not on the file system. '
//                    . 'You must warm the asset manager cache before running this. File: '
//                    . $file
//                );
//            }
//        }

        return $this->getJsonResponse($responseData);
    }

    protected function getJsonResponse($responseData)
    {
        return (new Response())->setContent(
            json_encode($responseData, JSON_PRETTY_PRINT)
        );
    }
}
