<?php

namespace app\models;

use MongoDB\BSON\UTCDateTime;
use yii\mongodb\ActiveRecord;


class WorkerModel extends ActiveRecord
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_INSERT = 'insert';

    public $sort = 500;
    public $active = true;


    /*
     select

  CONCAT("['id'=> ", id, ","),
  CONCAT("'name'=> '", name, "',"),
  CONCAT("'jobtitle'=> '", jobtitle, "',"),
  CONCAT("'code'=> '", code, "',"),
  CONCAT("'departament_id'=> ", departament_id, ","),
  CONCAT("'active'=> '", active, "',"),
  CONCAT("'date_create'=> ", 'NULL', ","),
  CONCAT("'date_update'=> '", CAST(date_update as DATETIME), "',"),
  CONCAT("'sort'=> ", sort, "],")

  from t_worker
  order by id
     */


    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'worker';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'name', 'departament_id', 'active', 'date_create', 'date_update', 'code', 'jobtitle', 'sort'];
    }


    /**
     * @return array Validation rules
     */
    public function rules()
    {
        //echo "getScenario: <pre>"; print_r(new \MongoDB\BSON\UTCDateTime(new \DateTime())); echo "</pre>";
        return [
            [['name', 'departament_id', 'code', 'jobtitle'], 'required'],
            ['departament_id', 'integer'],
            ['sort', 'default', 'value' => $this->sort],
            ['active', 'boolean'],
            ['active', 'default', 'value' => $this->active],
            ['date_update', 'required'],
            ['date_update', 'default', 'value' => new UTCDateTime(new \DateTime()), 'skipOnEmpty'=>false, 'on' => 'update'],
            [['date_create', 'date_update'], 'default', 'value' => new UTCDateTime(new \DateTime()), 'skipOnEmpty'=>false, 'on' => 'insert'],

            //['date_create', 'yii\mongodb\validators\MongoDateValidator', 'format' => 'MM/dd/yyyy'],
            //['_id', 'string'],
            //['jobtitle', function(){ return "efef"; }]
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            if($this->hasAttribute('date_create')){
                $this->date_create = new UTCDateTime(new \DateTime());
            }
        }

        if($this->hasAttribute('date_update')){
            $this->date_update = new UTCDateTime(new \DateTime());
        }

        return parent::beforeSave($insert);
    }

}