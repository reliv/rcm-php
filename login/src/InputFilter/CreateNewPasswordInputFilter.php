<?php

namespace RcmLogin\InputFilter;

use Zend\I18n\Validator\Alnum;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Class CreateNewPasswordInputFilter
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin\InputFilter
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class CreateNewPasswordInputFilter extends \Zend\InputFilter\InputFilter
{

    /**
     * CreateNewPasswordInputFilter constructor.
     */
    public function __construct()
    {
        $factory = new InputFactory();

        $this->add(
            $factory->createInput(
                [
                    'name' => 'password',
                    'required' => true,
                    'filters' => [
                        new \Zend\Filter\StripTags(),
                        new \Zend\Filter\StringTrim(),
                    ],
                    'validators' => [
                        new Alnum()
                    ]
                ]
            )
        );

        $this->add(
            $factory->createInput(
                [
                    'name' => 'passwordTwo',
                    'required' => true,
                    'filters' => [
                        new \Zend\Filter\StripTags(),
                        new \Zend\Filter\StringTrim(),
                    ],
                    'validators' => [
                        new Alnum()
                    ]
                ]
            )
        );
    }
}
