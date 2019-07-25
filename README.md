Yii2 module for blog, advanced template, backend blog management, SBadmin panel
===============================================================================

Ordinary management system for posts. Can be used as a base for any app.

Advantages
----------

* Posts, nested set rubrics, tags, users, authors;
* Comments management, update, reply;
* Imperavi redactor;
* Files & images uploading, photos chain for carousel slider;
* User registration with email confirmation or by network account;
* RBAC.

Installation
------------

After installation [Yii2 advanced template](https://github.com/yiisoft/yii2-app-advanced/blob/master/docs/guide/start-installation.md).

1. Change project composer file

Package has dev-master version and depends on the same packages, so in app directory change `composer.json`.

```
  "minimum-stability": "dev",
  "prefer-stable": true,
```

2. Install package

The preferred way to install this extension is through composer.

Either run

`composer require --prefer-dist sergmoro1/yii2-blog-tools`

or add

`"sergmoro1/yii2-blog-tools": "dev-master"`

to the require section of your composer.json.

3. Git init

`git init`

4. Configuring  migrations

Add information about migration folders with `namespace` to `console\config\main.php` before `components` section.

```php
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'sergmoro1\uploader\migrations',
                'sergmoro1\lookup\migrations',
                'sergmoro1\user\migrations',
                'sergmoro1\blog\migrations',
                'sergmoro1\comment\migrations',
            ],
        ],
    ],
```
 
5. Run migrations

```
php yii migrate
php yii migrate --migrationPath=@vendor/notgosu/yii2-meta-tag-module/src/migrations
```

6. Init blog

Copy predefined files to appropriate folders by batch file `initblog`.

To get it make a command in app directory.

`cp ./vendor/sergmoro1/yii2-blog-tools/src/initblog ./`

And run a batch file.

`php initblog`

Set the folder as writable to store the uploaded files.

`chmod -R 777 ./frontend/web/files`


Configuration
-------------

Set up in `backend/config/main.php`.

```php
return [
    'defaultRoute' => '/blog/site/index',
    'layoutPath' => '@vendor/sergmoro1/yii2-blog-tools/src/views/layouts',
    'modules' => [
        'uploader' => ['class' => 'sergmoro1\uploader\Module'],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['commentator', 'author', 'admin'],
            'itemFile' => __DIR__ . '/../../console/rbac/items.php',
            'ruleFile' => __DIR__ . '/../../console/rbac/rules.php',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
        ],
        'errorHandler' => [
            'errorAction' => '/blog/site/error',
        ],
    ],
];
```

Set up in `common/config/main.php`.

```php
return [
    'language' => 'ru-RU', // 'en-US',
    'bootstrap' => ['blog'],
    'modules' => [
        'lookup' => ['class' => 'sergmoro1\lookup\Module'],
        'blog' => ['class' => 'sergmoro1\blog\Module'],
        'user' => ['class' => 'sergmoro1\user\Module'],
        'seo' => [
            'class' => 'notgosu\yii2\modules\metaTag\Module',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'user' => [
            'class' => 'yii\web\User',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'viewPath' => '@vendor/sergmoro1/yii2-user/src/mail',
            /* Definition of Yandex post office for your domain (example).
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'admin@your-site.ru',
                'password' => 'your-password',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            */
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/../messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'metaTag' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                // sergmoro1/user/models/LoginForm is used in frontend/controllers/SiteController, so
                // it is not used within the Module then translation should be defined twice
                // here and in a sergmoro1/user/Module::registerTranslations()
                'sergmoro1/user/*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@vendor/sergmoro1/yii2-user/src/messages',
                    'fileMap' => [
                        'sergmoro1/user/core' => 'core.php',
                    ],
                ],
            ],
        ],
    ],
];
```

Don't forget add `.htaccess` file to `backend/web` and `frontend/web`.

Start
-----

Enter `http://your-app/backend/web` and `Login`.

**Name**: `Admin`

**Password**: `123456`
