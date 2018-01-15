<h1>Yii2 module for blog, advanced template. Backend blog management, SBadmin panel.</h1>

<h2>Advantages</h2>

Models
<ul>
  <li>post;</li>
  <li>comment;</li>
  <li>rubric (Nested Set);</li>
  <li>tag.</li>
</ul>

<h2>Installation</h2>

In app directory:

<pre>
$ composer require sergmoro1/yii2-blog-tools "dev-master"
// recomended
$ composer require sergmoro1/yii2-user "dev-master"
</pre>

<h3>Run migrations</h3>
<pre>
$ php yii migrate --migrationPath=@vendor/notgosu/yii2-meta-tag-module/src/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-byone-uploader/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-lookup/src/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-blog-tools/src/migrations
// if user module was installed
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-user/src/migrations
</pre>

<h3>Copy predefined files to appropriate folders</h3>

Copy files from <code>sergmoro1/yii2-blog-tools/src/temp</code> folders to appropriate folders of your project.

<h3>Configs</h3>

Set up in <code>backend/config/main.php</code> default layout, three modules and auth component.

<pre>
return [
    ...
    'layoutPath' => '@vendor/sergmoro1/yii2-blog-tools/src/views/layouts',
    ...
    'modules' => [
        'uploader' => ['class' => 'sergmoro1\uploader\Module'],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['author', 'admin'],
            'itemFile' => __DIR__ . '/../../console/rbac/items.php',
            'ruleFile' => __DIR__ . '/../../console/rbac/rules.php',
        ],
    ...
</pre>

Set up in <code>common/config/main.php</code> blog, user (if installed) and seo module.
<pre>
<?php
return [
    ...
    'bootstrap' => ['blog'],
    'modules' => [
        'lookup' => ['class' => 'sergmoro1\lookup\Module'],
        'blog' => ['class' => 'sergmoro1\blog\Module'],
        'user' => ['class' => 'sergmoro1\user\Module'],
        'seo' => [
            'class' => 'notgosu\yii2\modules\metaTag\Module',
            'viewPath' => '@backend/views/meta',
        ],
    ],
    ...
</pre>
