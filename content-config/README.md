# Content Config

Allows CMS pages to be rendered from JSON-compatible data embedded within route configurations without the need for any additional controllers or view templates.

## Usage

In any configuration file, you can add route configurations using the controller `Rcm\ContentConfig\ContentConfigController`.

The action chosen for this controller determines the way the controller will interpret the config and the way the config will be rendered. The following routes are supported, each one with additional params specific to that action:

### Action: `content-config`

Renders a complete CMS page using block instance configurations and layout information provided via the `content` and `containers` params.

```php
use Rcm\ContentConfig\ContentConfigController;

return [
    'router' => [
        'routes' => [
            'MyContentConfig' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/my-content-config',
                    'defaults' => [
                        'name' => 'MyContentConfig', // Not sure if required or optional. Better to add just in case.
                        'controller' => ContentConfigController::class,
                        'action' => 'content-config',
                        'rolesAllowed' => ['admin'], // Optional; if absent, all roles are allowed by default.
                        'title' => 'My Config Page', // Optional
                        'content' => [ // Main body content area; Required.
                            [ // Row 0
                                [ // Row 0, Column 0
                                    'block' => 'SomeBlockName', // Required.
                                    'columnClass' => 'col-sm-4', // Optional.
                                    'config' => [ // Optional.
                                        'foo' => 123
                                    ],
                                ],
                                [ /* ... additional columns for Row 0 ... */],
                            ],
                            [ /* ... additional rows for main body content area ... */ ],
                        ],
                        'containers' => [ // Optional
                            'someContainerName' => [
                                /** ... same format at 'content' key above ... */
                            ],
                            /** ... additional containers ... */
                        ]
                    ],
                ],
            ],
        ],
    ],
];
```

### Action: `react-client-block`

Renders a `<script>` tag that will make a single call to the client React renderer using a given block name and instance configuration.

The block doesn't have to have server-side configuration, just a client-side implementation that is registered with the client renderer. This allows you to describe an entire page using only React code and make it render using minimal server-side configuration.

```php
use Rcm\ContentConfig\ContentConfigController;

return [
    'router' => [
        'routes' => [
            'MyClientReactBlock' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/my-client-react-block',
                    'defaults' => [
                        'controller' => ContentConfigController::class,
                        'action' => 'client-react-block',
                        'rolesAllowed' => ['admin'], // Optional; if absent, all roles are allowed by default.
                        'title' => 'My Client React Block', // Optional
                        'block' => 'SomeBlockName',
                        'config' => [
                            'foo' => 123
                        ],
                    ],
                ],
            ],
        ],
    ],
];
```

## Helpers

Included with the module is a `Helpers` class that allows you to more compactly describe route configurations. The above two examples can also be expressed as follows:

```php
use Rcm\ContentConfig\Helpers;

return [
    'router' => [
        'routes' => [
            'MyContentConfig' => Helpers::routeContentConfig([
                'route' => '/my-content-config',
                'name' => 'MyContentConfigPage', // Not sure if required or optional. Better to add just in case.
                'rolesAllowed' => ['admin'], // Optional; if absent, all roles are allowed by default.
                'title' => 'My Config Page', // Optional
                'content' => [ // Main body content area; Required.
                    [ // Row 0
                        [ // Row 0, Column 0
                            'block' => 'SomeBlockName', // Required.
                            'columnClass' => 'col-sm-4', // Optional.
                            'config' => [ // Optional.
                                'foo' => 123
                            ],
                        ],
                        [ /* ... additional columns for Row 0 ... */],
                    ],
                    [ /* ... additional rows for main body content area ... */ ],
                ],
                'containers' => [ // Optional
                    'someContainerName' => [
                        /** ... same format at 'content' key above ... */
                    ],
                    /** ... additional containers ... */
                ]
            ]),
            'MyClientReactBlock' => Helpers::routeClientReactBlock([
                'route' => '/my-client-react-block',
                'block' => 'SomeBlockName',
                'rolesAllowed' => ['admin'], // Optional; if absent, all roles are allowed by default.
                'title' => 'My Client React Block', // Optional
                'config' => [
                    'foo' => 123
                ],
            ],
        ],
    ],
];
```
