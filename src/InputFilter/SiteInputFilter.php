<?php

namespace RcmAdmin\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class SiteInputFilter
 *
 * Site Input Filter
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
class SiteInputFilter extends InputFilter
{
    /**
     * @var array
     */
    protected $filterConfig = [
            //'siteId' => [],
            'domainName' => [
                'name' => 'domainName',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
            ],
            'languageIso6392t' => [
                'name' => 'languageIso6392t',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            'countryId' => [
                'name' => 'countryId',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            //
            'theme' => [
                'name' => 'theme',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            'siteLayout' => [
                'name' => 'siteLayout',
                'required' => false,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            'siteTitle' => [
                'name' => 'siteTitle',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            'status' => [
                'name' => 'status',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => \Zend\Validator\StringLength::class,
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
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            'loginPage' => [
                'name' => 'loginPage',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            'notAuthorizedPage' => [
                'name' => 'notAuthorizedPage',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
                ],
                'validators' => [

                ]
            ],
            'notFoundPage' => [
                'name' => 'notFoundPage',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class],
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
