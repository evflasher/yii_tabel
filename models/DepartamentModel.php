<?php

namespace app\models;

use MongoDB\BSON\UTCDateTime;
use yii\mongodb\ActiveRecord;


class DepartamentModel extends ActiveRecord
{
    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_INSERT = 'insert';

    public $sort = 500;
    public $active = true;


    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'departament';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'name', 'master_name', 'active', 'date_create', 'date_update', 'password', 'region_id', 'sort'];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование отдела',
            'master_name' => 'ФИО начальника отдела',
            'active' => 'Активность',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'password' => 'Пароль',
            'region_id' => 'Регион',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @return array Validation rules
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
            ['master_name', 'required'],
            ['master_name', 'string'],
            ['region_id', 'required'],
            ['region_id', 'string'],
            ['sort', 'integer'],
            ['sort', 'default', 'value' => $this->sort],
            ['password', 'string'],
            ['active', 'boolean'],
            ['active', 'default', 'value' => $this->active],
            ['date_update', 'default', 'value' => new UTCDateTime(new \DateTime()), 'skipOnEmpty'=>false, 'on' => 'update'],
            [['date_create', 'date_update'], 'default', 'value' => new UTCDateTime(new \DateTime()), 'skipOnEmpty'=>false, 'on' => 'insert'],
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

        if($this->hasAttribute('region_id')){
            $this->region_id = new \MongoDB\BSON\ObjectId($this->region_id);
        }

        return parent::beforeSave($insert);
    }

}