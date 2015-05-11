<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

use app\models\User;
use app\models\Dictionary;

class AnswerWrong extends ActiveRecord
{
    const COLLECTION = 'answer_wrong';

    public function collectionName() {
        return self::COLLECTION;
    }

    public function rules()
    {
        return [
            [['word_id', 'user_id', 'answer', 'type'], 'required'],
            [['answer'], 'string'],
            [['type'], 'in', 'range' => Dictionary::typesTranslate()],
            [['word_id'], 'exist', 'targetClass' => '\app\models\Dictionary', 'targetAttribute' => 'id'],
        ];
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}