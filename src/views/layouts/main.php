<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\User;
use sergmoro1\blog\widgets\Alert;
use sergmoro1\blog\widgets\Menu;

sergmoro1\blog\assets\SBAdminAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= Url::to('@web/favicon.ico') ?>" type="image/x-icon">
    
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(\Yii::$app->name . ' - ' . $this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>

<?php $this->beginBody() ?>
    <!-- Navigation -->

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= Url::to(['/blog/site/index']) ?>">
                    <?= Yii::$app->name ?>
                </a>
            </div>

            <!-- DropDown Menu -->
            <ul class="nav navbar-right top-nav">
                <?= Menu::widget([
                    'items' => \Yii::$app->params['dropdown'],
                    'view' => 'dropdown',
                    'markActive' => false, 
                    'replace' => [
                        '/user/user/update' => ['/user/user/update', 'id' => Yii::$app->user->id],
                    ],
                ]) ?>
            </ul>
            
            <!-- Sidebar Menu Items - these collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">

                <ul class="nav navbar-nav side-nav">
                <?php if(Yii::$app->user->isGuest || Yii::$app->user->identity->group == User::GROUP_COMMENTATOR): ?>

                    <li class='vertical'>
                        <h2><?= \Yii::$app->name ?></h2>
                        <p><?= \Yii::$app->params['slogan'] ?></p>
                    </li>
 
                <?php else: ?>

                    <?= Menu::widget([
                        'view' => 'sidebar', 
                        'items' => \Yii::$app->params['sidebar'],
                    ]) ?>

               <?php endif; ?>
                </ul>


            </div>

        </nav> <!-- /.navbar-collapse -->

        <div id="page-wrapper">

            <div class="container-fluid">
                
                <h3><?= $this->title ?></h3>
                
                <?= Alert::widget() ?>
                <?= $content ?>

            </div> <!-- /.container-fluid -->

        </div> <!-- /#page-wrapper -->

    </div> <!-- /#wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

