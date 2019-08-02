<?php
//
//namespace Rcm\CurrentRequest;
//
///**
// * @deprecated user "current request context" instead.
// *
// * Class ModuleConfig
// * @package Rcm\CurrentRequest
// */
//class ModuleConfig
//{
//    public function __invoke()
//    {
//        return [
//            'dependencies' => [
//                'factories' => [
//                    CurrentRequest::class => CurrentRequestFactory::class
//                ],
//                'config_factories' => [
//                    GetCurrentRequest::class => [],
//                    CurrentRequestEarlyMiddleware::class => [
//                        'arguments' => [
//                            GetCurrentRequest::class
//                        ]
//                    ]
//                ]
//            ]
//        ];
//    }
//}
