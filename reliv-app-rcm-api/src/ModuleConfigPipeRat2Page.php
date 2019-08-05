<?php

namespace Reliv\App\RcmApi;

use Rcm\Acl\ResourceProvider;
use Rcm\Api\Repository\Page\FindPageById;
use Rcm\Api\Repository\Page\PageExists;
use RcmAdmin\Service\PageMutationService;
use RcmUser\Api\Authentication\GetCurrentUser;
use Reliv\App\RcmApi\Page\PipeRat2\Api\FindOnePage;
use Reliv\App\RcmApi\Page\PipeRat2\Http\CopyPage;
use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Api\IsAllowedRcmUser;
use Reliv\PipeRat2\Acl\Http\RequestAcl;
use Reliv\PipeRat2\Core\Config\ConfigParams;
use Reliv\PipeRat2\DataExtractor\Api\ExtractByType;
use Reliv\PipeRat2\DataExtractor\Http\ResponseDataExtractor;
use Reliv\PipeRat2\DataValidate\Api\ValidateZfInputFilter;
use Reliv\PipeRat2\DataValidate\Http\RequestDataValidate;
use Reliv\PipeRat2\Repository\Config as Config;
use Reliv\PipeRat2\Repository\Http\RepositoryFindById;
use Reliv\PipeRat2\Repository\Http\RepositoryFindOne;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeFields;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedFields;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestValidAttributesAsserts;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributes;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributesValidate;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfigFromOptions;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfigByRequestFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;
use Reliv\PipeRat2\RequestFormat\Api\WithParsedBodyJson;
use Reliv\PipeRat2\RequestFormat\Http\RequestFormat;
use Reliv\PipeRat2\ResponseFormat\Api\WithFormattedResponseJson;
use Reliv\PipeRat2\ResponseFormat\Http\ResponseFormat;
use Reliv\PipeRat2\ResponseHeaders\Api\WithResponseHeadersAdded;
use Reliv\PipeRat2\ResponseHeaders\Http\ResponseHeaders;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfigPipeRat2Page
{
    protected $fieldsConfigProperties
        = [
            'pageId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'name' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'author' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'site' => [
                FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
                FieldConfig::KEY_INCLUDE => false,
                FieldConfig::KEY_PROPERTIES => [
                    'siteId' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                ],
            ],
            'createdDate' => [
                FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
                FieldConfig::KEY_INCLUDE => true,
                FieldConfig::KEY_PROPERTIES => [
                    'date' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'timezone' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                ],
            ],
            'createdDateString' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'lastPublished' => [
                FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
                FieldConfig::KEY_INCLUDE => true,
                FieldConfig::KEY_PROPERTIES => [
                    'date' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'timezone' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                ],
            ],
            'lastPublishedString' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'pageLayout' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'siteLayoutOverride' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'pageTitle' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'description' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'keywords' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'publishedRevisionId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'stagedRevisionId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'pageType' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'siteId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'parentId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
//            'currentRevision' => [
//                FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
//                FieldConfig::KEY_INCLUDE => true,
//            ],
        ];

    protected $filterConfig
        = [
            'name' => [
                'name' => 'name',
                'required' => true,
                'filters' => [],
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
                    [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'pageLayout' => [
                'name' => 'pageLayout',
                'required' => false,
                'filters' => [],
                'validators' => [
                    [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'siteLayoutOverride' => [
                'name' => 'siteLayoutOverride',
                'required' => false,
                'filters' => [],
                'validators' => [
                    [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'pageTitle' => [
                'name' => 'pageTitle',
                'required' => false,
                'filters' => [],
                'validators' => [
                    [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'description' => [
                'name' => 'description',
                'required' => false,
                'filters' => [],
                'validators' => [
                    [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'keywords' => [
                'name' => 'keywords',
                'required' => false,
                'filters' => [],
                'validators' => [
                    [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            'pageType' => [
                'name' => 'pageType',
                'required' => false,
                'filters' => [],
                'validators' => [
                    [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 1,
                        ],
                    ],
                ],
            ],
        ];

    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    FindOnePage::class => [
                        'arguments' => [
                            \Rcm\Api\Repository\Page\FindOnePage::class
                        ],
                    ],
                    CopyPage::class => [
                        'arguments' => [
                            FindPageById::class,
                            PageExists::class,
                            GetCurrentUser::class,
                            PageMutationService::class
                        ],
                    ],
                ],
            ],
            'routes' => [
                /**
                 * PATH: '/findOne'
                 * VERB: 'GET'
                 *  */
                'pipe-rat-2.rcm-page.find-one'
                => Config\RouteConfigFindOne::build(
                    'rcm-page',
                    [
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'options' => [
                            RequestAcl::configKey() => [
                                RequestAcl::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAcl::OPTION_SERVICE_OPTIONS
                                => [],
                            ],

                            RequestAttributes::configKey() => [
                                RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                                    WithRequestAttributeAllowedFieldConfig::class => [
                                        WithRequestAttributeAllowedFieldConfigFromOptions::OPTION_ALLOWED_FIELDS
                                        => [
                                            FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
                                            FieldConfig::KEY_PROPERTIES => $this->fieldsConfigProperties,
                                            FieldConfig::KEY_INCLUDE => true,
                                        ],
                                    ]
                                ],
                            ],

                            RepositoryFindOne::configKey() => [
                                RepositoryFindById::OPTION_SERVICE_NAME
                                => FindOnePage::class,

                                RepositoryFindById::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ],

                        'swagger' => [
                            'get' => [
                                'description' => 'Find One Page [pipe-rat-2] (@todo better swagger docs)',
                                'produces' => [
                                    'application/json',
                                ],
                                'parameters' => [],
                            ],
                        ],
                    ]
                ),
                /**
                 * PATH: '/{sourcePageId}/copy'
                 * VERB: 'POST'
                 *  */
                'pipe-rat-2.rcm-page.{sourcePageId}.copy'
                => ConfigParams::build(
                    [
                        'resource-name' => 'rcm-page',
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'name' => '{pipe-rat-2-config.root-path}.{pipe-rat-2-config.resource-name}.{sourcePageId}.copy',
                        'path' => '{pipe-rat-2-config.root-path}/{pipe-rat-2-config.resource-name}/{sourcePageId}/copy',
                        'middleware' => [
                            RequestFormat::configKey()
                            => RequestFormat::class,

                            RequestAcl::configKey()
                            => RequestAcl::class,

                            RequestAttributes::configKey()
                            => RequestAttributes::class,

                            RequestAttributesValidate::configKey()
                            => RequestAttributesValidate::class,

                            RequestDataValidate::configKey()
                            => RequestDataValidate::class,

                            /** <response-mutators> */
                            ResponseHeaders::configKey()
                            => ResponseHeaders::class,

                            ResponseFormat::configKey()
                            => ResponseFormat::class,

                            ResponseDataExtractor::configKey()
                            => ResponseDataExtractor::class,
                            /** </response-mutators> */

                            CopyPage::class
                            => CopyPage::class,
                        ],

                        'options' => [
                            RequestFormat::configKey() => [
                                RequestFormat::OPTION_SERVICE_NAME
                                => WithParsedBodyJson::class,

                                RequestFormat::OPTION_SERVICE_OPTIONS => [],
                            ],

                            RequestAcl::configKey() => [
                                RequestAcl::OPTION_SERVICE_NAME
                                => IsAllowedRcmUser::class,

                                RequestAcl::OPTION_SERVICE_OPTIONS => [
                                    IsAllowedRcmUser::OPTION_RESOURCE_ID => ResourceProvider::RESOURCE_PAGES,
                                    IsAllowedRcmUser::OPTION_PRIVILEGE => 'create',
                                ],
                            ],

                            RequestAttributes::configKey() => [
                                RequestAttributes::OPTION_SERVICE_NAMES => [
                                    WithRequestAttributeFields::class
                                    => WithRequestAttributeUrlEncodedFields::class,

                                    WithRequestAttributeAllowedFieldConfig::class
                                    => WithRequestAttributeAllowedFieldConfigFromOptions::class,

                                    WithRequestAttributeExtractorFieldConfig::class
                                    => WithRequestAttributeExtractorFieldConfigByRequestFields::class,
                                ],

                                RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                                    WithRequestAttributeAllowedFieldConfig::class => [
                                        WithRequestAttributeAllowedFieldConfigFromOptions::OPTION_ALLOWED_FIELDS
                                        => [
                                            FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
                                            FieldConfig::KEY_PROPERTIES => $this->fieldsConfigProperties,
                                            FieldConfig::KEY_INCLUDE => true,
                                        ],
                                    ]
                                ],
                            ],

                            RequestAttributesValidate::configKey() => [
                                RequestAttributesValidate::OPTION_SERVICE_NAME
                                => WithRequestValidAttributesAsserts::class,
                            ],

                            RequestDataValidate::configKey() => [
                                RequestDataValidate::OPTION_SERVICE_NAME
                                => ValidateZfInputFilter::class,

                                RequestDataValidate::OPTION_SERVICE_OPTIONS => [
                                    ValidateZfInputFilter::OPTION_INPUT_FILTER_CONFIG
                                    => $this->filterConfig
                                ],
                            ],

                            /** <response-mutators> */
                            ResponseHeaders::configKey() => [
                                ResponseHeaders::OPTION_SERVICE_NAME
                                => WithResponseHeadersAdded::class,

                                ResponseHeaders::OPTION_SERVICE_OPTIONS => [
                                    WithResponseHeadersAdded::OPTION_HEADERS => []
                                ],
                            ],

                            ResponseFormat::configKey() => [
                                ResponseFormat::OPTION_SERVICE_NAME
                                => WithFormattedResponseJson::class,

                                ResponseFormat::OPTION_SERVICE_OPTIONS => [],
                            ],
                            /** </response-mutators> */

                            ResponseDataExtractor::configKey() => [
                                ResponseDataExtractor::OPTION_SERVICE_NAME => ExtractByType::class,
                            ],
                        ],

                        'swagger' => [
                            'post' => [
                                'description' => 'Page Copy [pipe-rat-2] (@todo better swagger docs)',
                                'produces' => [
                                    'application/json',
                                ],
                                'parameters' => [],
                            ],
                        ],

                        'allowed_methods' => ['POST'],
                    ]
                ),
            ],
        ];
    }
}
