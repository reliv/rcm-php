<?php

namespace Reliv\App\RcmApi;

use Rcm\Api\GetSiteByRequest;
use Reliv\App\RcmApi\Site\PipeRat2\Api\FindByIdSite;
use Reliv\App\RcmApi\Site\PipeRat2\Api\FindOneSite;
use Reliv\App\RcmApi\Site\PipeRat2\Api\FindSites;
use Reliv\App\RcmApi\Site\PipeRat2\Http\RequestAttributeWhereMutatorSite;
use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Http\RequestAcl;
use Reliv\PipeRat2\Repository\Config as Config;
use Reliv\PipeRat2\Repository\Http\RepositoryFind;
use Reliv\PipeRat2\Repository\Http\RepositoryFindById;
use Reliv\PipeRat2\Repository\Http\RepositoryFindOne;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeFields;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeLimit;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeOrder;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeSkip;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedFields;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedLimit;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedOrder;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedSkip;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeUrlEncodedWhere;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhere;
use Reliv\PipeRat2\RequestAttribute\Api\WithRequestAttributeWhereMutator;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributes;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfigFromOptions;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeExtractorFieldConfigByRequestFields;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfigPipeRat2Site
{
    protected $fieldsConfigProperties
        = [
            'siteId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'domainId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'theme' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'siteLayout' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'siteTitle' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'languageId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'language' => [
                FieldConfig::KEY_TYPE => FieldConfig::OBJECT,
                FieldConfig::KEY_INCLUDE => true,
                FieldConfig::KEY_PROPERTIES => [
                    'languageId' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'languageName' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'iso6391' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'iso6392b' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'iso6392t' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                ],
            ],
            'countryId' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'status' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'favIcon' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'locale' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'loginPage' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'notAuthorizedPage' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'notFoundPage' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'supportedPageTypes' => [
                FieldConfig::KEY_TYPE => FieldConfig::COLLECTION,
                FieldConfig::KEY_INCLUDE => true,
                FieldConfig::KEY_PROPERTIES => [
                    'type' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'title' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                    'canClone' => [
                        FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                        FieldConfig::KEY_INCLUDE => true,
                    ],
                ],
            ],
        ];

    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [

                    FindByIdSite::class => [
                        'arguments' => [
                            \Rcm\Api\Repository\Site\FindSite::class
                        ],
                    ],

                    FindOneSite::class => [
                        'arguments' => [
                            \Rcm\Api\Repository\Site\FindOneSite::class
                        ],
                    ],

                    FindSites::class => [
                        'arguments' => [
                            \Rcm\Api\Repository\Site\FindSites::class
                        ],
                    ],

                    RequestAttributeWhereMutatorSite::class => [
                        'arguments' => [
                            GetSiteByRequest::class
                        ],
                    ],
                ],
            ],
            'routes' => [
                /**
                 * PATH: '/findOne'
                 * VERB: 'GET'
                 *  */
                'pipe-rat-2.rcm-site.find-one'
                => Config\RouteConfigFindOne::build(
                    'rcm-site',
                    [
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'middleware' => [],
                        'options' => [
                            RequestAcl::configKey() => [
                                RequestAcl::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAcl::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                            RequestAttributes::configKey() => [
                                RequestAttributes::OPTION_SERVICE_NAMES => [
                                    WithRequestAttributeFields::class
                                    => WithRequestAttributeUrlEncodedFields::class,

                                    WithRequestAttributeAllowedFieldConfig::class
                                    => WithRequestAttributeAllowedFieldConfigFromOptions::class,

                                    WithRequestAttributeExtractorFieldConfig::class
                                    => WithRequestAttributeExtractorFieldConfigByRequestFields::class,

                                    WithRequestAttributeWhere::class
                                    => WithRequestAttributeUrlEncodedWhere::class,

                                    WithRequestAttributeWhereMutator::class
                                    => RequestAttributeWhereMutatorSite::class,
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
                            RepositoryFindOne::configKey() => [
                                RepositoryFindById::OPTION_SERVICE_NAME
                                => FindOneSite::class,

                                RepositoryFindById::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ],
                        'swagger' => [
                            'get' => [
                                'description' => 'Find One Site [pipe-rat-2] (@todo better swagger docs)',
                                'produces' => [
                                    'application/json',
                                ],
                                'parameters' => [],
                            ],
                        ],
                    ]
                ),

                /**
                 * PATH: '/{id}'
                 * VERB: 'GET'
                 */
                'pipe-rat-2.rcm-site.find-by-id'
                => Config\RouteConfigFindById::build(
                    'rcm-site',
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
                            RepositoryFindById::configKey() => [
                                RepositoryFindById::OPTION_SERVICE_NAME
                                => FindByIdSite::class,

                                RepositoryFindById::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ],
                        'swagger' => [
                            'get' => [
                                'description' => 'Find Site by ID [pipe-rat-2] (@todo better swagger docs)',
                                'produces' => [
                                    'application/json',
                                ],
                                'parameters' => [],
                            ],
                        ],
                    ]
                ),

                /**
                 * PATH: '/'
                 * VERB: 'GET'
                 */
                'pipe-rat-2.rcm-site.find'
                => Config\RouteConfigFind::build(
                    'rcm-site',
                    [
                        'source-config-file' => __FILE__,
                    ],
                    [
                        'middleware' => [],
                        'options' => [
                            RequestAcl::configKey() => [
                                RequestAcl::OPTION_SERVICE_NAME
                                => IsAllowedAny::class,

                                RequestAcl::OPTION_SERVICE_OPTIONS
                                => [],
                            ],
                            RequestAttributes::configKey() => [
                                RequestAttributes::OPTION_SERVICE_NAMES => [
                                    WithRequestAttributeFields::class
                                    => WithRequestAttributeUrlEncodedFields::class,

                                    WithRequestAttributeAllowedFieldConfig::class
                                    => WithRequestAttributeAllowedFieldConfigFromOptions::class,

                                    WithRequestAttributeExtractorFieldConfig::class
                                    => WithRequestAttributeExtractorFieldConfigByRequestFields::class,

                                    WithRequestAttributeWhere::class
                                    => WithRequestAttributeUrlEncodedWhere::class,

                                    WithRequestAttributeWhereMutator::class
                                    => RequestAttributeWhereMutatorSite::class,

                                    WithRequestAttributeOrder::class
                                    => WithRequestAttributeUrlEncodedOrder::class,

                                    WithRequestAttributeSkip::class
                                    => WithRequestAttributeUrlEncodedSkip::class,

                                    WithRequestAttributeLimit::class
                                    => WithRequestAttributeUrlEncodedLimit::class,
                                ],
                                RequestAttributes::OPTION_SERVICE_NAMES_OPTIONS => [
                                    WithRequestAttributeAllowedFieldConfig::class => [
                                        WithRequestAttributeAllowedFieldConfigFromOptions::OPTION_ALLOWED_FIELDS
                                        => [
                                            FieldConfig::KEY_TYPE => FieldConfig::OBJECT_COLLECTION,
                                            FieldConfig::KEY_PROPERTIES => $this->fieldsConfigProperties,
                                            FieldConfig::KEY_INCLUDE => true,
                                        ],
                                    ]
                                ],
                            ],
                            RepositoryFind::configKey() => [
                                RepositoryFind::OPTION_SERVICE_NAME
                                => FindSites::class,

                                RepositoryFind::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ],

                        'swagger' => [
                            'get' => [
                                'description' => 'Find Site [pipe-rat-2] (@todo better swagger docs)',
                                'produces' => [
                                    'application/json',
                                ],
                                'parameters' => [],
                            ],
                        ],
                    ]
                ),
            ],
        ];
    }
}
