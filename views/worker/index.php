<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\grid\GridView;

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker_list list-table-wrap">
    <div class="list-table-top">
        <nav class="nav nav-pills nav-justified">
            <?= Html::a('Добавить сотрудника', ['create', 'back_url' => yii\helpers\Url::current()], ['class' => 'btn btn-sm btn-outline-success']) ?>
        </nav>
    </div>
    <table class="table table-hover">
        <thead>
            <th></th>
            <th>Сотрудник</th>
            <th>Активен</th>
            <th>Должность</th>
            <th>Отдел</th>
            <th>Табельный номер</th>
            <th>Сорт.</th>
            <th>Дата создания</th>
            <th>Дата изменения</th>
        </thead>
        <tbody>
            <?foreach ($arResult as $worker){?>
                <tr>
                    <td></td>
                    <td><a href="/index.php?r=worker/update&id=<?=$worker->_id?>"><?= Html::encode($worker->name)?></a></td>
                    <td><?=$worker->active?></td>
                    <td><?=$worker->jobtitle?></td>
                    <td><?=$worker->departament_id?></td>
                    <td><?=$worker->code?></td>
                    <td><?=$worker->sort?></td>
                    <td><?=Yii::$app->formatter->asDatetime($worker->date_create->toDateTime())?></td>
                    <td><?=Yii::$app->formatter->asDatetime($worker->date_update->toDateTime())?></td>
                </tr>
            <?}?>

        </tbody>

    </table>



</div>
<?= yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
