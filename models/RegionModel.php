<?php

namespace app\models;

use MongoDB\BSON\UTCDateTime;
use yii\mongodb\ActiveRecord;


class RegionModel extends ActiveRecord
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_INSERT = 'insert';

    public $sort = 500;
    public $default = false;


    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'regions';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'name', 'date_create', 'default', 'sort'];
    }


    /**
     * @return array Validation rules
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['sort', 'integer'],
            ['sort', 'default', 'value' => $this->sort],
            ['active', 'boolean'],
            ['default', 'default', 'value' => $this->default],
            ['date_create', 'default', 'value' => new UTCDateTime(new \DateTime()), 'skipOnEmpty'=>false, 'on' => 'insert'],
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            if($this->hasAttribute('date_create')){
                $this->date_create = new UTCDateTime(new \DateTime());
            }
        }

        return parent::beforeSave($insert);
    }

}