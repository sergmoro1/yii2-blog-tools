<?php

/* @var $this \yii\web\View */
/* @var $content string */

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\User;
use common\widgets\Alert;
use sergmoro1\langswitcher\widgets\LangSwitcher;

backend\assets\AppAsset::register($this);
sergmoro1\blog\assets\SBAdminAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= Url::to('@web/favicon.ico') ?>" type="image/x-icon">
    
	<!-- Custom Fonts -->
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(\Yii::$app->name . ' - ' . $this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?= LangSwitcher::widget(); ?>

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
                <a class="navbar-brand" href="<?= Url::to(['/site/index']) ?>">
					<?= Yii::$app->name ?>
				</a>
				<a class="language-switcher" href="<?= Url::to(['/langswitcher/language/switch']) ?>">rus|eng</a>
            </div>

            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
				<li><?= Html::a(Yii::t('app', 'frontend'), ['/blog/site/frontend']) ?></li>
				<?php if(Yii::$app->user->isGuest): ?>
				<li><?= Html::a(Yii::t('app', 'Login'), ['/user/site/login']) ?></li>
				<?php else: ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php if($image = Yii::$app->user->identity->getImage('thumb')): ?>
							<img class="img-circle avatar" src="<?= $image ?>">
						<?php else: ?>
							<i class="fa fa-user"></i>
						<?php endif; ?>
						<?= Yii::$app->user->identity->name ?>
						<b class="caret"></b>
					</a>
                    <ul class="dropdown-menu">
						<?php if(Yii::$app->user->can('gear')): ?>
						    <li><?= Html::a('<i class="fa fa-gear"></i> ' . Yii::t('app', 'Gear'), ['/site/gear']) ?></li>
						<?php else: ?>
							<li><?= Html::a('<i class="fa fa-user"></i> ' . Yii::t('app', 'Profile'), ['/user/user/index']) ?><li>
						<?php endif; ?>
                        <li class="divider"></li>
                        <li>
							<li><?= Html::a('<i class="fa fa-fw fa-power-off"></i> ' . Yii::t('app', 'Logout'), ['/user/site/logout'], ['data-method' => 'post']) ?></li>
                        </li>
                    </ul>
                </li>
				<?php endif; ?>
			</ul>

            <!-- Sidebar Menu Items - these collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">

				<?php if(Yii::$app->user->isGuest): ?>
                <ul class="nav navbar-nav side-nav">

					<li class='vertical'>
						<h2><?= Yii::t('app', 'Blog') ?></h2>
						<p><?= Yii::t('app', 'Websites development') ?></p>
					</li>
				</ul>

				<?php else: ?>

					<?= sergmoro1\blog\widgets\Menu::widget(['items' => \Yii::$app->params['sidebar']]) ?>

				<?php endif; ?>


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

