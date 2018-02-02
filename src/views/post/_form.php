<style>
    .en {color: #556b2f;}
</style>
<?php
/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use sergmoro1\uploader\widgets\Byone;
use sergmoro1\blog\Module;

use sergmoro1\blog\models\Rubric;
use sergmoro1\lookup\models\Lookup;

?>

<?php $form = ActiveForm::begin(); ?>

<div class='row'>
<div class="col-lg-8">

    <div class="form-group">
        <?= Html::submitButton(Module::t('core', 'Save'), [
            'class' => 'btn btn-success',
        ]) ?>
    </div>

    <?= Byone::widget([
        'model' => $model,
        'minFileSize' => 0.005,
        'maxFileSize' => 5, // 5Mb
        'draggable' => true,
        'appendixView' => '/post/appendix.php',
        'cropAllowed' => true,
    ]) ?>
    <br>
    
    <?= $form->field($model, 'title')
        ->textInput(['maxlength' => true])
        ->hint(Module::t('core', 'Part of the title can be selected by [] as a link of the post'))
    ?>

    <?= $form->field($model, 'subtitle')
        ->textInput(['maxlength' => true]) 
    ?>

    <?= $form->field($model, 'slug')
        ->textInput(['maxlength' => true])
    ?>

    <?= $form->field($model, 'previous')->dropdownList($model->CanBePrevious(), [
        'prompt' => Module::t('core', 'Select'),
    ]); ?>

    <?= $form->field($model, 'excerpt')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'fullscreen',
            ]
        ]
    ]); ?>

    <?= $form->field($model, 'content')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'replaceDivs' => false,
            'fileUpload' => Url::to(['site/file-upload']),            
            'imageUpload' => Url::to(['site/image-upload']),
            'fileDelete' => Url::to(['site/delete-file']),
            'imageDelete' => Url::to(['site/delete-file']),
            'fileManagerJson' => Url::to(['site/get-files']),        
            'imageManagerJson' => Url::to(['site/get-images']),        
            'plugins' => [
                'video',
                'table',
                'fullscreen',
            ],
        ],
        'plugins' => [
            'filemanager' => 'vova07\imperavi\bundles\FileManagerAsset',              
            'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',              
        ],
    ]); ?>

    <?= $form->field($model, 'resume')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 100,
            'plugins' => [
                'fullscreen',
            ]
        ]
    ]); ?>

    <?= backend\widgets\metaTagForm\Widget::widget([
        'model' => $model,
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('core', 'Save'), [
            'class' => 'btn btn-success',
        ]) ?>
    </div>
</div>

<div class="post-files col-lg-4">

    <?= $form->field($model, 'created_at_date') ?>

    <?= $form->field($model, 'status')->dropdownList(
        Lookup::items('PostStatus')
    ); ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rubric')->dropdownList(
        Rubric::items(Module::t('core', 'Root'))
    ); ?>

</div>
</div>

<?php ActiveForm::end(); ?>
