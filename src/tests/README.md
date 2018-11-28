<h1>How to start tests</h1>

codecept run<br>
codecept run acceptance<br>
codecept run acceptance CommentCept --steps --debug

<h2>Configs, switch off Pretty Ur</h2>

Set <code>enablePrettyUrl</code> to <code>false</code> or comment it in a file <code>backend/config/main.php</code>.
<pre>
  'components' => [
    'urlManager' => [
      'class' => 'yii\web\UrlManager',
      'enablePrettyUrl' => false,
</pre>

In a file <code>common/config/main.php</code> comment <code>LangSwitcher</code> if this component is used.
<pre>
  'bootstrap' => [
    //'LangSwitcher',
    'blog',
</pre>

<h2>Database</h2>

Create database <code>yii2_advanced_tests</code> and start migrations in a root direcory of the application.
<pre>
php yii migrate
//  all others as mentioned in vendor/sergmoro1/yii2=blog-tools/README.md
</pre>

Change database name in <code>./common/config/main-local.php</code> to

<pre>
return [
  'components' => [
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=127.0.0.1;dbname=yii2_advanced_tests,
      'username' => 'root',
      'password' => '', //set it if needed
      'charset' => 'utf8',
</pre>

<h2>yml</h2>

Change, if needed, a <code>password</code> in: 

tests/acceptance.suite.yml
tests/functional.suite.yml
tests/unit.suite.yml

Change backend <code>url</code> in <code>tests/acceptance.suite.yml</code>. In my case it will be <code>http://sm1/vorst</code>

<pre>
    config:
        PhpBrowser:
            url: http://localhost
</pre>

<h2>Namespace</h2>

After <code>*.suite.yml</code> have changed then automatically can be changed namespaces in a folder 
<code>sergmoro1\blog\tests\_support\_generated</code>. Fix namespaces if needed.
For example in <code>AcceptanceTesterActions.php</code> from
<pre>
namespace sergmoro1\blog\tests\_generated;
</pre>

to
<pre>
namespace sergmoro1\blog\tests\_support\_generated;
</pre>
