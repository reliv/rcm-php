<?php
/**
 * Created by PhpStorm.
 * User: rmcnew
 * Date: 1/14/14
 * Time: 4:13 PM
 */

namespace Rcm\View\Helper;


use Zend\View\Helper\AbstractHelper;

class OutOfDateBrowserWarning extends AbstractHelper
{
    const LOWEST_IE_VERSION = 8;

    public function __invoke()
    {
        return '
        <!--[if lt IE 8]>
            <div style="text-align:center;width:100%;padding:5px 10px;z-index:9999;background-color:#FDF2AB;position:absolute;border-bottom:1px solid #A29330">
                ' . $this->getMessage() . '
            </div>
        <![endif]-->
        ';
    }

    public function getMessage()
    {
        return
            'This website does not work with Internet Explorer versions below '
            . self::LOWEST_IE_VERSION . '. Please update your browser
            or install the latest Chrome or Firefox web browser.';
    }
} 