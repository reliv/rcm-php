RCM
==============

Add these entries to the "require" section inside "path-to-zf2-project-root/composer.json"

```php
"require": {
    "reliv/Rcm" : "dev-master"
}
```

Tell Composer to download the required packages
```bash
php /path-to-zf2-project-root/composer.phar update
```

Run this to set the correct permissions for folders that Apache/PHP must be able to write to
```bash
mkdir  /path-to-zf2-project-root/data
mkdir  /path-to-zf2-project-root/data/DoctrineORMModule
mkdir  /path-to-zf2-project-root/data/DoctrineORMModule/Proxy
chmod 777 /path-to-zf2-project-root/data -R

mkkdir /path-to-zf2-project-root/public/modules
chmod 777 /path-to-zf2-project-root/public/modules
```

Add this "path-to-zf2-project-root/config/application.config.php"
```php
<?php
return array(
    'modules' => array(
        //Rcm Dependencies
        'DoctrineModule',
        'DoctrineORMModule',

        //RCM core and plugins
        'Rcm',
        'RcmPluginCommon',
        'RcmHtmlArea',
        'RcmNavigation',
        'RcmLogin',
        'RcmSocialButtons',
        'RcmRssFeed',
```

Add this to "path-to-zf2-project-root/config/autoload/local.php"
```php
<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'DATABASE-USERNAME',
                    'password' => 'DATABASE-PASSWORD',
                    'dbname'   => 'DATABASE-DBNAME',
                )
            )
        ),
    ),
    'encryption' => array(
        'blockCypher' => array(
            'algo' => 'aes',
            'key' => 'CHANGE THIS ENCRYPTION KEY!'
        )
    ),
);
```

To avoid issues with Doctrine, make sure "date.timezone" is set in your php.ini.

```php
date.timezone="America/Chicago"
```

Navigate to this URL in a browser to run the installer. This loads data into your database and creates symlinks that allow zf2 modules to contain routable public asset folders.

http://localhost/rcm/install


Login with these credentials:
```php
Email: admin@admin.com
Password: admin
```