<?php

namespace RcmUser\Ui\View\Helper;

use RcmUser\Ui\Service\RcmUserHtml;
use RcmUser\Ui\Service\RcmUserHtmlService;
use Zend\View\Helper\AbstractHelper;

/**
 * @deprecated Use RcmUserHtml->buildDefaultHtmlHead()
 * Class RcmUserBuildHtmlHead
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Ui\View\Helper
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmUserBuildHtmlHead extends AbstractHelper
{
    /**
     * @var RcmUserHtmlService
     */
    public $rcmUserHtmlService;

    /**
     * @var null|\Zend\View\Renderer\RendererInterface
     */
    public $view;

    /**
     * RcmUserBuildHtmlHead constructor.
     *
     * @param RcmUserHtmlService $rcmUserHtmlService
     */
    public function __construct(RcmUserHtmlService $rcmUserHtmlService)
    {
        $this->rcmUserHtmlService = $rcmUserHtmlService;
        $this->view = $this->getView();
    }

    /**
     * buildHtmlHead
     *
     * @return void
     */
    public function buildHtmlHead()
    {
        $this->rcmUserHtmlService->buildDefaultHtmlHead($this->view);
    }

    /**
     * includeHead
     *
     * @param $type
     * @param $path
     *
     * @return void
     */
    public function includeHead(
        $type,
        $path
    ) {
        $this->rcmUserHtmlService->includeHead($this->view, $type, $path);
    }

    /**
     * @deprecated Use RcmUserHtml->buildDefaultHtmlHead()
     * __invoke
     *
     * @param array $options options
     *
     * @return mixed
     */
    public function __invoke(
        $options = []
    ) {
        $this->buildHtmlHead();
    }
}
