<?php

namespace RcmAdmin\InputFilter;

use Zend\InputFilter\InputFilter;

class SitePageUpdateInputFilter extends InputFilter
{
    /**
     * @var array
     */
    protected $filterConfig = [
        'name' => [
            'name' => 'name',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => \Zend\Validator\Regex::class,
                    'options' => [
                        'pattern' => '/[a-z\-0-9\.]/',
                        'messageTemplates' => [
                            \Zend\Validator\Regex::NOT_MATCH
                            => "Page name can only contain letters and dashes"
                        ]
                    ],
                ],
            ]
        ],
        'pageLayout' => [
            'name' => 'pageLayout',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
            'validators' => [
            ]
        ],
        'siteLayoutOverride' => [
            'name' => 'siteLayoutOverride',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
            'validators' => [
            ]
        ],
        'pageTitle' => [
            'name' => 'pageTitle',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
            'validators' => [
            ]
        ],
        'description' => [
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
            'validators' => [
            ]
        ],
        'keywords' => [
            'name' => 'keywords',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
            'validators' => [
            ]
        ],
        'pageType' => [
            'name' => 'pageType',
            'required' => false,
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
        'publicReadAccess' => [
            'name' => 'publicReadAccess',
            'required' => false,
            'allow_empty' => true,
            'continue_if_empty' => true,
            'validators' => [
                [
                    'name' => \Zend\Validator\InArray::class,
                    'options' => ['haystack' => [true, false]]
                ]
            ]
        ],
        'readAccessGroups' => [
            'name' => 'readAccessGroups',
            'required' => false,
            'validators' => [
                [
                    'name' => \Zend\Validator\InArray::class,
                    'options' => [
                        'haystack' => [
                            ['employee', 'distributor', 'customer'],
                            ['employee', 'distributor'],
                            ['employee'],
                            [],
                            null
                        ]
                    ]
                ]
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
