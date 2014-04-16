<?php
/**
 * Created by PhpStorm.
 * User: rmcnew
 * Date: 4/16/14
 * Time: 11:29 AM
 */

namespace Rcm\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AbstractRcmEdit extends AbstractHelper
{
    function buildEdit(
        $name,
        $content,
        $elementType = 'div',
        $elementAttributes = array(),
        $isRichEdit = false
    )
    {
        $elementType = htmlentities($elementType, ENT_QUOTES);

        $attributes = ' '
            . ($isRichEdit ? 'data-richEdit' : 'data-textEdit')
            . '="' . htmlentities($name, ENT_QUOTES) . '"';
        foreach ($elementAttributes as $key => $value) {
            $attributes .= ' ' . htmlentities($key, ENT_QUOTES)
                . '="' . htmlentities($value, ENT_QUOTES) . '"';
        }

        return '<' . $elementType . $attributes . '>'
        . $content
        . ' </' . $elementType . ' > ';
    }
}