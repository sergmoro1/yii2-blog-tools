<h1>Yii2 module for blog, advanced template. Backend blog management, SBadmin panel.</h1>

<h2>Advantages</h2>

Full featured.

Models
<ul>
  <li>post;</li>
  <li>comment;</li>
  <li>rubric (Nested Set);</li>
  <li>tag;</li>
  <li>meta tag.</li>
</ul>

Files & Images
<ul>
  <li>Upload, resize, sorting by mouse, cropping;</li>
  <li>Imperavi redactor with predefined controller - upload, select, delete files & images.</li>
</ul>

SEO
<ul>
  <li>Any metatags;</li>
  <li>Metatags management.</li>
</ul>

RBAC
<ul>
  <li>Predefined roles, rules;</li>
  <li>Predefined controllers with rights verification.</li>
</ul>

User (if installed)
<ul>
  <li>Registration with email confirmation;</li>
  <li>Authorization;</li>
  <li>Images uploading.</li>
</ul>

Comment
<ul>
  <li>For Post (but for any model);</li>
  <li>Comment management;</li>
  <li>Ability to reply comments.</li>
</ul>

<h2>Installation</h2>

Change project <code>composer.json</code> parameter
<pre>
    "minimum-stability": "dev",
</pre>

In app directory:

<pre>
$ composer require sergmoro1/yii2-blog-tools "dev-master"
// recomended
$ composer require sergmoro1/yii2-user "dev-master"
</pre>

<h3>Run migrations</h3>

In app directory:

<pre>
$ php yii migrate --migrationPath=@vendor/notgosu/yii2-meta-tag-module/src/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-byone-uploader/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-lookup/src/migrations
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-blog-tools/src/migrations
// if user module was installed
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-user/src/migrations
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
</pre>

<h3>Configs</h3>

Set up in <code>backend/config/main.php</code> default layout, uploader module and auth component.

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
            'viewPath' => '@backend/views/meta',
        ],
    ],
    ...
</pre>

<h3>Start</h3>

Enter <code>http://your-app/backend/web</code> and <code>Login</code>.

Name: Admin

Password: 123456
