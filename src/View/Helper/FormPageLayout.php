<?php
/**
 * Form View Helper for the Page Layout Element
 *
 * This file contains the unit test for Form View Helper for the Page Layout Element
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdmin\View\Helper;

use RcmAdmin\Form\Element\MainLayout;
use Zend\Form\ElementInterface;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\View\Helper\FormMultiCheckbox;

/**
 * Form View Helper for the Page Layout Element
 *
 * Form View Helper for the Page Layout Element
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class FormPageLayout extends FormMultiCheckbox
{
    /**
     * Return input type
     *
     * @return string
     */
    protected function getInputType()
    {
        return 'radio';
    }

    /**
     * Get element name
     *
     * @param ElementInterface $element Zend Form Element Interface
     *
     * @return string
     */
    protected static function getName(ElementInterface $element)
    {
        return $element->getName();
    }

    /**
     * Over writes render method to put layout array into the correct format for
     * Zend Form
     *
     * @param ElementInterface $element Zend Element Interface
     *
     * @return string|void
     * @throws InvalidArgumentException
     */
    public function render(ElementInterface $element)
    {
        if (!$element instanceof MainLayout) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type PageLayout',
                    __METHOD__
                )
            );
        }

        $options = $element->getOptions();
        $element->setLabelOption('disable_html_escape', true);

        $inputOptions = [];

        foreach ($options['layouts'] as $key => &$layout) {
            $inputOptions[$key]
                = "\n" . '<span class="pageLayoutLabel">' . "\n"
                . '    <img src="' . $layout['screenShot'] . '" />' . "\n"
                . '    <span class="pageLayoutTextDisplay">' . "\n"
                . '        ' . $layout['display'] . "\n"
                . '    </span>' . "\n"
                . '    <span class="pageLayoutImageOverlay"></span>' . "\n"
                . '</span>' . "\n";
        }


        $name = static::getName($element);

        $options = $inputOptions;

        $attributes = $element->getAttributes();
        $attributes['name'] = $name;
        $attributes['type'] = $this->getInputType();
        $selectedOptions = (array)$element->getValue();

        $rendered = $this->renderOptions(
            $element,
            $options,
            $selectedOptions,
            $attributes
        );

        return $rendered;
    }
}
