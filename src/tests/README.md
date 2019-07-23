Test manual
===========

How to start tests.

```
codecept run
codecept run acceptance
codecept run acceptance CommentCept --steps --debug
```

Before start
------------

Before you start the tests, you must create a database and run migrations.

Create database `yii2_advanced_tests`.
Change database name in `./common/config/main-local.php` to `yii2_advanced_tests`.
Start migrations in a root direcory of the application as mentioned in `sergmoro1\blog\README.md`.

Configuration
-------------

Set `enablePrettyUrl` to `false` or comment it in a file `backend/config/main.php`.

```php
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => false,
```

In a file `common/config/main.php` comment `LangSwitcher` if this component is used.

```php
    'bootstrap' => [
        //'LangSwitcher',
        'blog',
```

yml
---

Change, if needed, a `password` in test config files. 

```
tests/acceptance.suite.yml
tests/functional.suite.yml
tests/unit.suite.yml
```

Change `webRoot` of the application in `tests/acceptance.suite.yml`.

```
  config:
    PhpBrowser:
      url: http://localhost/your-app
```

Namespace
---------

After `*.suite.yml` have changed then automatically can be changed namespaces in a folder 
`sergmoro1\blog\tests\_support\_generated`. Fix namespaces if needed.
For example in `AcceptanceTesterActions.php`

```
// from
namespace sergmoro1\blog\tests\_generated;

//to
namespace sergmoro1\blog\tests\_support\_generated;
```
