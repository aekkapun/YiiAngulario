<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;

use yii\filters\VerbFilter;

use app\models\Result;

class ResultController extends ActiveController
{   
    public $modelClass = 'app\models\Result';

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
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            return true;
        }

        $model = new Result();
        $model->load(Yii::$app->request->post());
        $model->user_id = Yii::$app->user->id;

        if(!$model->save()) {
            return $model->getErrors();
        } 

        return true;
    }
}
