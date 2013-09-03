<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'memory'=> true,
                )
            )
        )
    ),
    'encryption' => array(
        'blockCypher' => array(
            'algo' => 'aes',
            'key' => 'mLo8VALnCxcd6NR4FPPdRnU8OO7kBzIlHIV89yrd5te1OKZvgfrPYAZDc'
        ),
        'cookieBlockCypher'=>array(
            'algo' => 'aes',
            'key' => 'eSxY6o2HDhlKrIPgBZgq11hUyH7SSbEiUlTCxu5aKpJhiYvd5htUMx8YZ'
        )
    ),

    'rcmCache' => array(
        'adapter' => 'Memory',
    ),


    'view_manager' => array(
        'base_path' => '/'
    )
);
