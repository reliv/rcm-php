<?php

namespace RcmAdmin\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class SitePageCreateInputFilter
 *
 * SitePageCreateInputFilter
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
class SitePageCreateInputFilter extends InputFilter
{
    /**
     * @var array
     */
    protected $filterConfig
        = [
            'name' => [
                'name' => 'name',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/[^a-z\-0-9\.]/i',
                            'messageTemplates' => array(
                                \Zend\Validator\Regex::NOT_MATCH
                                => "Username can only contain letters and dashes"
                            )
                        ),
                    ),
                ]
            ],
            'pageLayout' => [
                'name' => 'pageLayout',
                'required' => false,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                ]
            ],
            'siteLayoutOverride' => [
                'name' => 'siteLayoutOverride',
                'required' => false,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                ]
            ],
            'pageTitle' => [
                'name' => 'pageTitle',
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                ]
            ],
            'description' => [
                'name' => 'description',
                'required' => false,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                ]
            ],
            'keywords' => [
                'name' => 'keywords',
                'required' => false,
                'filters' => [
                    ['name' => 'Zend\Filter\StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                ]
            ],
            'pageType' => [
                'name' => 'pageType',
                'required' => false,
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
