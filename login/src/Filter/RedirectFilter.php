<?php

namespace RcmLogin\Filter;

use Zend\Filter\FilterInterface;
use Zend\Validator\ValidatorInterface;

/**
 * Filter for login redirect
 *
 * This filter cleans up passed redirect urls and ensures that
 * successful logins are not redirected away from the site
 *
 * @category  Reliv
 * @package   RcmLogin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RedirectFilter implements FilterInterface
{
    /** @var ValidatorInterface */
    protected $validator;

    /**
     * RedirectFilter constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Filter the redirect url
     *
     * @param mixed $value
     * @return string|null
     */
    public function filter($value)
    {
        if (!$this->validator->isValid($value)) {
            return null;
        }

        return urldecode(filter_var($value, FILTER_SANITIZE_URL));
    }
}
