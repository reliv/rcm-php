<?php

namespace RcmAdmin\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * View helper to display zend form errors
 *
 * View helper to display zend form errors
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class DisplayErrors extends AbstractHelper
{
    /**
     * Invoke method
     *
     * @param array $errors Array of errors to display
     *
     * @return null|string
     */
    public function __invoke($errors)
    {
        return $this->renderErrors($errors);
    }

    /**
     * Render errors in html
     *
     * @param array $errors Array of errors to display
     *
     * @return null|string
     */
    public function renderErrors($errors)
    {
        if (empty($errors)) {
            return null;
        }

        $message = '';

        foreach ($errors as &$error) {
            foreach ($error as $errorCode => &$errorMsg) {
                $message .= $this->errorMapper($errorCode, $errorMsg);
            }

        }

        return $message;
    }

    /**
     * Map specific errors to html wrappers
     *
     * @param string $errorCode Error code
     * @param string $errorMsg  Error message
     *
     * @return string
     */
    public function errorMapper($errorCode, $errorMsg)
    {
        switch ($errorCode) {
            case 'pageName':
            case 'pageExists':
                return
                    '<p class="urlErrorMessage">' . $errorMsg . '</p>' . "\n";

            default:
                return '<p class="errorMessage">' . $errorMsg . '</p>' . "\n";
        }
    }
}
