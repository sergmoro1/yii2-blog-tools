<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use sergmoro1\blog\Module;

$this->title = Module::t('core', 'Backend');
$this->params['noTitle'] = true;
?>
<div class="site-index">

    <div class="row">
        <div class="col-sm-12">
            <h2>Cтатьи</h2>
            <p>
                Список постов, фильтры, предпросмотр. Добавление картинок и фото.
            </p>
            <p>
                Вложенные множества, метки для ускорения поиска. Цепочки постов для связывания статей по смыслу.
            </p>

            <p><?= Html::a('list &raquo;', ['blog/post/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h2>Комментарии</h2>
            <p>
                Коментарий может оставить любой без регистрации.
            </p>
            <p>
                Комментарий публикуется после подтверждения.
            </p>
            <p>
                Зарегистрированные пользователи могут оставлять ответы на комментарии к собственным постам.
            </p>

            <p><?= Html::a('list &raquo;', ['blog/comment/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h2>Пользователи</h2>
            <p>
                Полный профайл. Любое количество фото.
            </p>
            <p>
                Две роли - пользователь и админ. Ваши посты может изменить только автор и админ.
            </p>
            <p>
                Регистрация с подтверждением по email.
            </p>

            <p><?= Html::a('list &raquo;', ['user/user/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

</div>
