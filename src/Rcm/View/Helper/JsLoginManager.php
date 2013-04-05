<?php
namespace Rcm\View\Helper;
class JsLoginManager extends \Zend\Form\View\Helper\AbstractHelper
{
    public function __invoke()
    {
        /** @var \Zend\View\Renderer\PhpRenderer $renderer */
        $renderer = $this->getView();
        $renderer->headScript()->appendFile(
            $renderer->basePath() . '/modules/rcm/js/rcm-login-mgr.js',
            'text/javascript'
        );
        return '
        <script type="text/javascript">
        window["rcmLoginMgr"] = new RcmLoginMgr(
                "'. $renderer->url('contentManagerLogin') .'"
            );
        </script>';
    }
}
