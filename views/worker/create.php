<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\WorkerModel */
/* @var $departament app\models\DepartamentModel */

$this->title = 'Новый сотрудник ';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <?if($model->hasErrors()){?>
        <div class="alert alert-danger">
            <p>Ошибка сохранения:</p>
            <?
            echo join($model->getErrorSummary(true), "<br>");
            ?>
        </div>
    <?}?>

    <?php $form = ActiveForm::begin(['options' => ['class' => 'needs-validation', 'novalidate'=>true]]); ?>

    <div class="form-row">
        <div class="col-md-6">
            <?= $form->field($model, 'departament_id')->dropDownList(
                ArrayHelper::map($departament, '_id', 'nazvanie'), ['prompt' => 'Выберите отдел...'])?>

            <?= $form->field($model, 'active')->checkbox() ?>

            <?= $form->field($model, 'sort')->textInput() ?>
            <?= $form->field($model, 'name')->textInput() ?>


            <?= $form->field($model, 'jobtitle')->textInput() ?>
            <?= $form->field($model, 'code')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <a class="btn btn-outline-secondary" href="/index.php?r=worker/index">Отменить</a>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
