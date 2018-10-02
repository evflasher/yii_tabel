<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorkerModel */

$this->title = 'Сотрудник ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['class' => 'needs-validation', 'novalidate'=>true]]); ?>

    <div class="form-row">
        <div class="col-md-6">
            <?/*= $form->field($model, 'organizaciya')->dropDownList(
                ArrayHelper::map($orgname, 'id', 'nazvanie'), ['prompt' => 'Выберите организацию...']) */?>

            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Дата создания</label>
                <div class="col-sm-8">
                    <input type="text" readonly class="form-control-plaintext" value="<?=Yii::$app->formatter->asDatetime($model->date_create->toDateTime())?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Дата изменения</label>
                <div class="col-sm-8">
                    <input type="text" readonly class="form-control-plaintext" value="<?=Yii::$app->formatter->asDatetime($model->date_update->toDateTime())?>" />
                </div>
            </div>

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
