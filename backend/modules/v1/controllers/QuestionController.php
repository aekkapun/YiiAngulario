<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;

use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;

use app\models\Dictionary;

class QuestionController extends ActiveController
{   
    public $modelClass = 'app\models\Dictionary';
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                QueryParamAuth::className(),
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            return true;
        }
        
        return new ActiveDataProvider([
            'query' => Dictionary::find(),
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);
    }
}
