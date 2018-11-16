<?php 
/*
 * Sidebar Menu.
 */
use yii\helpers\Html;
use sergmoro1\blog\Module;
?>

<?php foreach($items as $name => $item): ?>

	<?php if(isset($item['admin']) && $item['admin'] && !(\Yii::$app->user->identity->group == common\models\User::GROUP_ADMIN)) continue; ?>

	<li <?= $url == $item['url'] ? 'class="active"' : '' ?>>
		<?= Html::a('<span class="'. $item['icon'] .'"></span> '. Module::t('core', $item['caption']), ['/' . $item['url']]); ?>
	</li>
<?php endforeach; ?>
