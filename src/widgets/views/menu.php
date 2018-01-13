<?php 
/*
 * Sidebar menu.
 */

use yii\helpers\Html;
?>

<ul class="nav navbar-nav side-nav">

	<?php foreach($items as $name => $item): ?>

		<?php if(isset($item['admin']) && $item['admin'] && !(Yii::$app->user->identity->group == common\models\User::GROUP_ADMIN)) continue; ?>

		<li <?= Yii::$app->request->pathInfo == $item['url'] ? 'class="active"' : '' ?>>
			<?= Html::a('<i class="fa fa-1fw fa-'. $item['icon'] .'"></i> '. \Yii::t('app', $item['caption']), ['/' . $item['url']]); ?>
		</li>
	<?php endforeach; ?>

</ul>
