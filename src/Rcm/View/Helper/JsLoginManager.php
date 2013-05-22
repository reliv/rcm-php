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
        /*
         * Couldn't use the url() zf2 helper here because of a bug in it for scheme routes
         */
        return '
        <script type="text/javascript">
        window["rcmLoginMgr"] = new RcmLoginMgr(
                "https://'. $_SERVER['HTTP_HOST'] .'/login/auth"
            );
        </script>';
    }
}
