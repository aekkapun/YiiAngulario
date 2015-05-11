<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Dictionary extends ActiveRecord
{
    const COLLECTION = 'dictionary';


    public function collectionName() {
        return self::COLLECTION;
    }

    public function fields()
    {   
        return [
            'id',
            'text_eng',
            'text_rus',
            'type' => function(){
                $types = self::typesTranslate();
                return $types[array_rand($types)];
            },
            'variants' => function ($model) {
                $total = ['id' => $model->id, 'text_eng' => $model->text_eng, 'text_rus' => $model->text_rus];
                $variants = $this->getRandomWords($model->id);
                $variants[] = $total;
                shuffle($variants);
                return $variants;
            },
        ];
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function typesTranslate()
    {
        return ['text_rus.text_eng', 'text_eng.text_rus'];
    }

    private function getRandomWords($total_id)
    {   
        /*Custom random query for big data*/
        /*
        $sql = 'SELECT *
                  FROM '.self::COLLECTION.' AS r1 JOIN
                       (SELECT CEIL(RAND() *
                                     (SELECT MAX(id)
                                        FROM '.self::COLLECTION.')) AS id)
                        AS r2
                 WHERE r1.id >= r2.id AND r1.id NOT IN ('.$total_id.')
                 ORDER BY r1.id ASC
                 LIMIT '. Yii::$app->params['randomWords'];
        $words = Yii::$app->db->createCommand($sql)->queryAll();
        */
        $words = (new \yii\db\Query())
            ->select(['*'])
            ->from(self::COLLECTION)
            ->where(['NOT IN', 'id', $total_id])
            ->limit(Yii::$app->params['randomWords'])
            ->orderBy('RAND()')
            ->all();
           
        return ($words) ? $words : [];
    }
}