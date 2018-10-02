<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\DepartamentModel;
use app\models\RegionModel;
use yii\web\NotFoundHttpException;

class DepartamentController extends Controller
{
    public function actionIndex()
    {
        /** @var \yii\mongodb\ActiveQuery $query */
        $query = DepartamentModel::find();

        $pagination = new Pagination([
            'defaultPageSize' => 15,
            'totalCount' => $query->count()
        ]);

        $dep = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'arResult' => $dep,
            'pagination' => $pagination
        ]);
    }

    public function actionCreate()
    {
        $model = new DepartamentModel();
        $model->setScenario($model::SCENARIO_INSERT);
        //$model->validate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(["index"]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'regions' => RegionModel::find()->getCollection()->aggregate([      // https://docs.mongodb.com/manual/meta/aggregation-quick-reference/
                    [ '$project' => [
                        '_id' => [ '$toString' => '$_id' ],
                        'name' => '$name',
                    ] ]
                ])
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario($model::SCENARIO_UPDATE);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index'/*, 'id' => (string)$model->_id*/]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'regions' => RegionModel::find()->getCollection()->aggregate([      // https://docs.mongodb.com/manual/meta/aggregation-quick-reference/
                    [ '$project' => [
                        '_id' => [ '$toString' => '$_id' ],
                        'name' => '$name',
                    ] ]
                ])
            ]);
        }
    }

    /**
     * Finds the primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DepartamentModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DepartamentModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}