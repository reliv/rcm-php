<?php

namespace Reliv\App\RcmApi;

use Rcm\Api\Repository\Country\FindCountryByIso3;
use Reliv\App\RcmApi\Country\PipeRat2\Api\FindByIso3Country;
use Reliv\App\RcmApi\Country\PipeRat2\Api\FindCountries;
use Reliv\App\RcmApi\Country\PipeRat2\Api\FindOneCountry;
use Reliv\PipeRat2\Acl\Api\IsAllowedAny;
use Reliv\PipeRat2\Acl\Http\RequestAcl;
use Reliv\PipeRat2\Repository\Config as Config;
use Reliv\PipeRat2\Repository\Http\RepositoryFind;
use Reliv\PipeRat2\Repository\Http\RepositoryFindById;
use Reliv\PipeRat2\Repository\Http\RepositoryFindOne;
use Reliv\PipeRat2\RequestAttribute\Http\RequestAttributes;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfig;
use Reliv\PipeRat2\RequestAttributeFieldList\Api\WithRequestAttributeAllowedFieldConfigFromOptions;
use Reliv\PipeRat2\RequestAttributeFieldList\Service\FieldConfig;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfigPipeRat2Country
{
    protected $fieldConfigProperties
        = [
            'id' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'countryName' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'iso2' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
            'iso3' => [
                FieldConfig::KEY_TYPE => FieldConfig::PRIMITIVE,
                FieldConfig::KEY_INCLUDE => true,
            ],
        ];

    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [

                    FindByIso3Country::class => [
                        'arguments' => [
                            FindCountryByIso3::class
                        ],
                    ],

                    FindCountries::class => [
                        'arguments' => [
                            \Rcm\Api\Repository\Country\FindCountries::class
                        ],
                    ],

                    FindOneCountry::class => [
                        'arguments' => [
                            \Rcm\Api\Repository\Country\FindOneCountry::class
                        ],
                    ],
                ],
            ],

            'routes' => [
                /**
                 * PATH: '/findOne'
                 * VERB: 'GET'
                 *  */
                'pipe-rat-2.rcm-country.find-one'
                => Config\RouteConfigFindOne::build(
                    'rcm-country',
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
                                            FieldConfig::KEY_PROPERTIES => $this->fieldConfigProperties,
                                            FieldConfig::KEY_INCLUDE => true,
                                        ],
                                    ]
                                ],
                            ],
                            RepositoryFindOne::configKey() => [
                                RepositoryFindById::OPTION_SERVICE_NAME
                                => FindOneCountry::class,

                                RepositoryFindById::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ],
                        'swagger' => [
                            'get' => [
                                'description' => 'Find One RCM Country [pipe-rat-2] (@todo better swagger docs)',
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
                'pipe-rat-2.rcm-country.find-by-id'
                => Config\RouteConfigFindById::build(
                    'rcm-country',
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
                                            FieldConfig::KEY_PROPERTIES => $this->fieldConfigProperties,
                                            FieldConfig::KEY_INCLUDE => true,
                                        ],
                                    ]
                                ],
                            ],
                            RepositoryFindById::configKey() => [
                                RepositoryFindById::OPTION_SERVICE_NAME
                                => FindByIso3Country::class,

                                RepositoryFindById::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ],
                        'swagger' => [
                            'get' => [
                                'description' => 'Find RCM Country by ID [pipe-rat-2] (@todo better swagger docs)',
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
                'pipe-rat-2.rcm-country.find'
                => Config\RouteConfigFind::build(
                    'rcm-country',
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
                                            FieldConfig::KEY_TYPE => FieldConfig::OBJECT_COLLECTION,
                                            FieldConfig::KEY_PROPERTIES => $this->fieldConfigProperties,
                                            FieldConfig::KEY_INCLUDE => true,
                                        ],
                                    ]
                                ],
                            ],
                            RepositoryFind::configKey() => [
                                RepositoryFind::OPTION_SERVICE_NAME
                                => FindCountries::class,

                                RepositoryFind::OPTION_SERVICE_OPTIONS => [],
                            ],
                        ],
                        'swagger' => [
                            'get' => [
                                'description' => 'Find RCM Country [pipe-rat-2] (@todo better swagger docs)',
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
