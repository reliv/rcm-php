<?php

namespace RcmLogin\Form;

use Zend\Form\Element;

/**
 * Class LabelHelper
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class LabelHelper
{
    /**
     * __invoke
     *
     * @param Element $formElement
     * @param         $instanceConfig
     *
     * @return string
     */
    public function getHtml(Element $formElement, $instanceConfig)
    {
        $labelName = $inputId = $formElement->getAttribute('name');
        $fieldSetParts = explode('[', $labelName);

        /**
         * Handle elements that came from fieldSets
         */
        if (count($fieldSetParts) > 1) {
            $fieldSetParts = explode(']', $fieldSetParts[1]);
            $labelName = $fieldSetParts[0] . $fieldSetParts[1];
            $inputId = $fieldSetParts[0];
        }

        $instanceConfigLabelName = $labelName . 'Label';

        $text = $instanceConfig[$instanceConfigLabelName];

        return '<label for="' . $inputId . '"'
        . (count($formElement->getMessages()) ? ' class=invalid' : '')
        . ' data-textEdit="' . $instanceConfigLabelName . '">'
        . $text
        . '</label>';
    }
}
