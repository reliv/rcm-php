<?php
/**
 * rcm-user-config.php
 */
return [
    'Acl\Config' => [
        'ResourceProviders' => [
            Rcm\Acl\ResourceProvider::class => \Rcm\Acl\ResourceProvider::class,
        ],
    ],
];
