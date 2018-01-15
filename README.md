<h1>Yii2 module for blog. Backend blog management, admin panel.</h1>

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
</pre>

Run migration:
<pre>
$ php yii migrate --migrationPath=@vendor/sergmoro1/yii2-blog-tools/migrations
</pre>

<h2>Usage</h2>

Set up in <code>backend/config/main.php</code> default layout and tree modules. Two of them was setted up automatically.

<pre>
return [
    ...
    'layoutPath' => '@vendor/sergmoro1/yii2-blog-tools/src/views/layouts',
    ...
    'modules' => [
		'uploader' => ['class' => 'sergmoro1\uploader\Module'],
		'lookup' => ['class' => 'sergmoro1\lookup\Module'],
		'blog' => ['class' => 'sergmoro1\blog\Module'],
    ],
</pre>

