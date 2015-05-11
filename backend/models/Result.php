<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Result extends ActiveRecord
{
    const COLLECTION = 'result';

    public function collectionName() {
        return self::COLLECTION;
    }

    public function rules()
    {
        return [
            [['user_id', 'points', 'max_points'], 'required'],
            [['points', 'max_points'], 'integer'],
        ];
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}