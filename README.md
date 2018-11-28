<h1>Yii2 module for blog, advanced template. Backend blog management, SBadmin panel.</h1>

<h2>Advantages</h2>

Posts, comments, rubrics, tags, users, authors. 

Imperavi redactor. Files & Images uploading. Photos chain for carousel slider.

Comment management. Nested set rubric.

Avatars for users and authors. User registration with email confirmation.

RBAC.

<h2>Installation</h2>

After installation <a href='https://github.com/yiisoft/yii2-app-advanced/blob/master/docs/guide/start-installation.md'>Yii2 advanced template</a>.

<h3>Change project composer file</h3>

Package has dev-master version and depends on the same packages, so

In app directory change <code>composer.json</code>:

<pre>
  "minimum-stability": "dev",
  "prefer-stable": true,
</pre>

<h3>Install package</h3>

<pre>
$ composer require --prefer-dist sergmoro1/yii2-blog-tools "dev-master"
</pre>

<h3>Run migrations</h3>

<pre>
$ php yii migrate --migrationPath=@vendor/notgosu/yii2-meta-tag-module/src/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-byone-uploader/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-lookup/src/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-user/src/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-blog-tools/src/migrations
</pre>

<h3>Git init</h3>

<pre>
$ git init
</pre>

<h3>Copy predefined files to appropriate folders</h3>

In app directory:

<pre>
$ cp ./vendor/sergmoro1/yii2-blog-tools/src/initblog ./
$ php initblog
$ chmod -R 777 ./frontend/web/files
$ chmod 777 ./frontend/config/params.php
</pre>

<h3>Configs</h3>

Set up in <code>backend/config/main.php</code>.

<pre>
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
    'errorHandler' => [
      'errorAction' => '/blog/site/error',
    ],
  ],
];
</pre>

Set up in <code>common/config/main.php</code>.
<pre>
return [
  ...
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
</pre>

<h3>Start</h3>

Enter <code>http://your-app/backend/web</code> and <code>Login</code>.

Name: Admin

Password: 123456
