<?php
/**
 * Author: Pavel Naumenko
 * Add columns & Collapsed panel: Sergey Morozov
 *
 * @var MetaTagContent[] $metaTagModelList
 * @var \yii\db\ActiveRecord $model
 * @var array $languageList
 * @var string $defaultLanguage
 */
use notgosu\yii2\modules\metaTag\models\MetaTagContent;
use yii\helpers\Html;

$out= [];
foreach($languageList as $language)
	$out[$language] = "<fieldset><legend>$language</legend>";
foreach ($model->metaTags as $i => $data) {
    $out[$data->language] .= Html::beginTag('div', ['class' => 'form-group']);

    switch ($data->metaTag->name) {
        case \notgosu\yii2\modules\metaTag\models\MetaTag::META_ROBOTS:
            $out[$data->language] .= Html::activeCheckbox($model, 'metaTags[' . $i . '][content]',
                ['class' => 'form-control', 'label' => 'robots no index, FOLLOW', 'labelOptions' => ['class' => 'form-inline']]);
            break;
        default:
            $out[$data->language] .= Html::activeLabel(
                $model,
                'metaTags[' . $i . '][content]',
                ['class' => 'control-label', 'label' => $data->metaTag->name]
            );

            $out[$data->language] .= Html::activeTextarea($model, 'metaTags[' . $i . '][content]', ['class' => 'form-control', 'rows' => 6]);
            break;
    }

    $out[$data->language] .= Html::endTag('div');
}
foreach($languageList as $language)
    $out[$language] .= "</fieldset>";
?>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel-default"> <!-- panel -->
	<div class="panel-heading" role="tab" id="headingSEO">
	  <h4 class="panel-title">
		<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSEO" aria-expanded="true" aria-controls="collapseOne">
		  SEO rus|eng
		</a>
	  </h4>
	</div>
	<div id="collapseSEO" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSEO">
      <div class="row">
      <?php foreach($languageList as $language): ?>
	    <div class="col-sm-<?= floor(12 / count($languageList)) ?>">
		  <?= $out[$language]?>
	    </div>
      <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
