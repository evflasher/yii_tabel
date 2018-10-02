<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\DepartamentModel */

$this->title = 'Отдел ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
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
            <?= $form->field($model, 'region_id')->dropDownList(
                ArrayHelper::map($regions, '_id', 'name'), ['prompt' => 'Выберите регион...'])?>

            <?= $form->field($model, 'active')->checkbox() ?>

            <?= $form->field($model, 'sort')->textInput() ?>
            <?= $form->field($model, 'name')->textInput() ?>


            <?= $form->field($model, 'master_name')->textInput() ?>
            <?= $form->field($model, 'password')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <a class="btn btn-outline-secondary" href="/index.php?r=worker/index">Отменить</a>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
