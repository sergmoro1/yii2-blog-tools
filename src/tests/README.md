<h1>How to start tests</h1>

codecept run
codecept run acceptance
codecept run acceptance CommentCept --steps --debug

<h2>Database</h2>

Create database "yii2_advanced_tests" and start migrations as mentioned in README.md.

Change database name in <code>your_project/common/config.php</code> to

<pre>
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=yii2_advanced_tests,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],

</pre>

<h2>yml</h2>

Change, if needed, username and password in: 

tests/acceptance.suite.yml
tests/functional.suite.yml
tests/unit.suite.yml

Change backend url in tests/acceptance.suite.yml

<pre>
    config:
        PhpBrowser:
            url: http://localhost
</pre>

<h2>Namespace</h2>

After <code>*.suite.yml</code> have changed then automatically can be changed namespaces in a folder 
<code>sergmoro1\blog\tests\_support\_generated</code>. Fix namespaces if needed.

<h2>Switch off Pretty Url</h2>

Comment Url Manager <code>backend/config/main.php</code>.

<pre>
return [
    'components' => [
        /*
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
        ],
        */
</pre>
