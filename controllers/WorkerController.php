<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\WorkerModel;
use yii\web\NotFoundHttpException;

class WorkerController extends Controller
{
    public function actionIndex()
    {
//        $newWorker = new Worker();
//        $newWorker->name = 'Ефименко Е.В.';
//        $newWorker->departament_id = 49;
//        $newWorker->insert();



        /** @var \yii\mongodb\ActiveQuery $query */
        $query = WorkerModel::find();

        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' => $query->count()
        ]);

        $worker = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        //echo "<br><br><br><br>: <pre>"; print_r(); echo "</pre>";

        return $this->render('index', [
            'arResult' => $worker,
            'pagination' => $pagination
        ]);
    }

//    public function actionView($id){
//        return $this->render('view', [
//            'model' => $this->findModel($id)
//        ]);
//    }

    public function actionCreate($option = [])
    {
        $model = new WorkerModel();
        $model->setScenario($model::SCENARIO_INSERT);
        //$model->validate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$option["back_url"]?$option["back_url"]:"index"]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'departament' => [],
            ]);
        }
    }

    public function actionUpdate($option = [])
    {
        $model = $this->findModel($option["id"]);
        $model->setScenario($model::SCENARIO_UPDATE);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index'/*, 'id' => (string)$model->_id*/]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionImport(){
        /** @var \yii\mongodb\Collection $collection */
        $collection = \Yii::$app->mongodb->getCollection('worker');

        $collection->batchInsert([
            ['id'=> 2,	'name'=> 'Жидкова Е.В.',	'jobtitle'=> 'Бухгалтер-материалист',	'code'=> '900000248',	'departament_id'=> 1,	'active'=> 'Y',	'date_create'=> NULL,	'date_update'=> '2018-09-05 17:50:15',	'sort'=> 500]


        ]);

        return $this->render('index', [

        ]);
    }

    /**
     * Finds the primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkerModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkerModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}