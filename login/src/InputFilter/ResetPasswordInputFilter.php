<?php


namespace RcmLogin\InputFilter;

use Zend\InputFilter\Factory as InputFactory;

/**
 * ResetPasswordInputFilter
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin\InputFilter
 * @copyright 2017 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

class ResetPasswordInputFilter extends \Zend\InputFilter\InputFilter
{
    public function __construct()
    {

        $factory = new InputFactory();

        $this->add(
            $factory->createInput(
                [
                    'name' => 'userId',
                    'required' => true,
                    'filters' => [
                        new \Zend\Filter\StripTags(),
                        new \Zend\Filter\StringTrim(),
                    ],
                ]
            )
        );
    }
}
