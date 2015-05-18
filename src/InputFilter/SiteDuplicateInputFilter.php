<?php

namespace RcmAdmin\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class SiteDuplicateInputFilter
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class SiteDuplicateInputFilter extends InputFilter
{
    /**
     * @var array
     */
    protected $filterConfig = [
            'siteId' => [
                'name' => 'siteId',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\ToInt'],
                ],
            ],
            // These have special formats - so we custom validate
            'domain' => [
                'name' => 'domain',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
//                'validators' => [
//                    [
//                        'name' => 'Hostname',
//                        'options' => [
//                        ],
//                    ],
//                ]
            ],
            'language' => [
                'name' => 'language',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            'country' => [
                'name' => 'country',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            //
            'theme' => [
                'name' => 'theme',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            'siteLayout' => [
                'name' => 'siteLayout',
                'required' => false,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            'siteTitle' => [
                'name' => 'siteTitle',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            'status' => [
                'name' => 'status',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 1,
                        ],
                    ],
                ]
            ],
            'favIcon' => [
                'name' => 'favIcon',
                'required' => false,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            'loginPage' => [
                'name' => 'loginPage',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            'notAuthorizedPage' => [
                'name' => 'notAuthorizedPage',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
            'notFoundPage' => [
                'name' => 'notFoundPage',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [

                ]
            ],
        ];

    /**
     *
     */
    public function __construct()
    {
        $this->build();
    }

    /**
     * build input filter from config
     *
     * @return void
     */
    protected function build()
    {
        $factory = $this->getFactory();

        foreach ($this->filterConfig as $field => $config) {
            $this->add(
                $factory->createInput(
                    $config
                )
            );
        }
    }
}
