<?php
/**
 * Rcm edit view helper
 *
 * This creates editable text areas in views and also ensures that their content
 * is stripped of javascript in order to prevent XSS attacks.
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\ViewHelper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Rcm edit view helper
 *
 * This creates editable text areas in views and also ensures that their content
 * is stripped of javascript in order to prevent XSS attacks.
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmInstanceConfig\ViewHelper
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RcmEdit extends AbstractHelper
{
    protected $htmlPurifier;
    protected $editAttributeKey;

    /**
     * RcmEdit Constructor
     *
     * @param \HTMLPurifier $htmlPurifier html purifier class for striping web script
     * @param bool          $isRichEdit   true if the edit is a rich edit
     */
    public function __construct(
        \HTMLPurifier $htmlPurifier,
        $isRichEdit = false
    ) {
        $this->htmlPurifier = $htmlPurifier;
        $this->editAttributeKey = ($isRichEdit ? 'data-richEdit'
            : 'data-textEdit');
    }

    /**
     * Creates an Rcm editable html element
     *
     * This also filters the element's content to remove any malicious web script.
     *
     * @param string $name              Used as key in instance config
     * @param null   $defaultContent    Default content if not in instance config
     * @param string $elementType       Html element type
     * @param array  $elementAttributes Html element attributes
     *
     * @return string
     */
    public function __invoke(
        $name,
        $defaultContent = null,
        $elementType = 'div',
        $elementAttributes = []
    ) {
        /**
         * Use whats saved in the instance config if its available
         */
        if (array_key_exists($name, $this->view->instanceConfig)) {
            $defaultContent = $this->view->instanceConfig[$name];
        }

        /**
         * Build the html element's attributes
         */
        $attributes = ' ' . $this->editAttributeKey
            . '="' . htmlentities($name, ENT_QUOTES) . '"';
        foreach ($elementAttributes as $key => &$value) {
            $attributes .= ' ' . htmlentities($key, ENT_QUOTES)
                . '="' . htmlentities($value, ENT_QUOTES) . '"';
        }

        /**
         * Return element's html
         */
        $elementType = htmlentities($elementType, ENT_QUOTES);
        return '<' . $elementType . $attributes . '>'
//        . $this->htmlPurifier->purify($defaultContent)
        . $defaultContent //This is a hole and is temporary
        . '</' . $elementType . '>';
    }
}
