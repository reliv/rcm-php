<?php

namespace Rcm\ContentConfig;

use Zend\Mvc\Router\Http\Segment;

/**
 * Static helper methods for ConfigPages.
 */
class Helpers
{
    /**
     * Generate a ZF2 route config that produces a content-config page
     *
     * Example for a full page configuration:
     * ```php
     *      Helpers::routeConfigPage([
     *          'route' => '/my-config-page',
     *          'title' => 'My Config Page',
     *          'content' => [
     *              [ // Row 0
     *                  [ // Row 0, Column 0
     *                      'block' => 'BlockName',
     *                      'config' => [ ...block config... ]
     *                  ], // Row 0, End column 0
     *                  [ ...Row 0, Column 1... ]
     *              ], // End row 0
     *              [ ...Row 1... ],
     *          ],
     *      ]);
     * ```
     *
     * @param array $config
     * @return array
     * @see ContentConfigController
     */
    public static function routeContentConfig(array $config = []): array
    {
        return static::route(array_merge_recursive([
            'action' => 'content-config',
        ], $config));
    }

    /**
     * Generate a ZF2 route config that produces a client React block page
     *
     * Example for a full page configuration:
     * ```php
     *      Helpers::routeClientReactBlock([
     *          'route' => '/my-block-page',
     *          'title' => 'My Block Page',
     *          'block' => 'MyBlock'
     *          'config' => [ ...block config... ],
     *      ]);
     * ```
     *
     * @param array $config
     * @return array
     * @see ContentConfigController
     */
    public static function routeClientReactBlock(array $config = []): array
    {
        return static::route(array_merge_recursive([
            'action' => 'client-react-block'
        ], $config));
    }

    protected static function route(array $config): array
    {
        $route = $config['route'];
        unset($config['route']);
        return [
            'type' => Segment::class,
            'options' => [
                'route' => $route,
                'defaults' => array_merge_recursive([
                    'controller' => ContentConfigController::class,
                ], $config),
            ],
        ];
    }
}
