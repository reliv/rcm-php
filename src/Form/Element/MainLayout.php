<?php
/**
 * Custom Zend Form Element for Main Layout
 *
 * This file contains a Custom Zend Form Element for Main Layout
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
namespace RcmAdmin\Form\Element;

use Zend\Form\Element\Radio;

/**
 * Custom Zend Form Element for Main Layout
 *
 * Custom Zend Form Element for Main Layout
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class MainLayout extends Radio
{

    /**
     * Set options for an element. Accepted options are:
     * - label: label to associate with the element
     * - label_attributes: attributes to use when the label is rendered
     * - layouts: list of values and labels for the radio options
     *
     * @param array|\Traversable $options Options to set
     *
     * @return MainLayout
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        $value_options = [];

        if (isset($this->options['layouts'])) {
            foreach ($this->options['layouts'] as $key => &$layout) {
                $value_options[$key] = $layout['display'];
            }
        }

        $this->setValueOptions($value_options);

        return $this;
    }
}
