<?php
/**
 * Rcm Page Name Validator
 *
 * This file contains the class definition for the Page Name Validator
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Rcm Page Name Validator
 *
 * Rcm Page Name Validator. This validator will verify that the page
 * name meets requirements.  Page names can only be alpha numeric and only
 * contain the additional characters of "-_"
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class PageName extends AbstractValidator
{
    const PAGE_NAME = 'pageName';

    protected $messageTemplates
        = [
            self::PAGE_NAME => "'%value%' is not a valid page name."
        ];

    protected $pageNameOk = false;

    /**
     * Is the page name valid?
     *
     * @param mixed $value Value to be checked
     *
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        $pattern = '/^[a-z0-9_\-]*[a-z0-9]$/i';

        $this->pageNameOk = true;

        if (!preg_match($pattern, $value)) {
            $this->error(self::PAGE_NAME);
            $this->pageNameOk = false;
        }

        return $this->pageNameOk;
    }
}
