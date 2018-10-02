<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\DepartamentModel */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Отделы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="departament_list list-table-wrap">
    <div class="list-table-top">
        <nav class="nav nav-pills nav-justified">
            <?= Html::a('Добавить отдел', ['create', 'back_url' => yii\helpers\Url::current()], ['class' => 'btn btn-sm btn-outline-success']) ?>
        </nav>
    </div>
    <table class="table table-hover">
        <thead>
            <th></th>
            <th>Наименование</th>
            <th>Начальник</th>
            <th>Активность</th>
            <th>Сорт.</th>
            <th>Дата изменения</th>
        </thead>
        <tbody>
            <?foreach ($arResult as $departament){?>
                <tr>
                    <td></td>
                    <td><?= Html::a(Html::encode($departament->name), ['update', 'id' => (string)$departament->_id, 'back_url' => yii\helpers\Url::current()]) ?></td>
                    <td><?=$departament->master_name?></td>
                    <td><?=$departament->active?></td>
                    <td><?=$departament->sort?></td>
                    <td><?=Yii::$app->formatter->asDatetime($departament->date_update->toDateTime())?></td>
                </tr>
            <?}?>

        </tbody>

    </table>



</div>
<?= yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
