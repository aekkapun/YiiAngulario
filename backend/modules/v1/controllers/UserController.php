<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use app\models\User;
use app\models\LoginForm;

class UserController extends Controller
{   
    public $modelClass = 'app\models\User';
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'login' => ['POST', 'OPTIONS'],
            ],   
        ];

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => ['login'],
            'authMethods' => [
                QueryParamAuth::className(),
            ],
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            return true;
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return User::findOne(['email' => $model->email]);
        } else {
            $model->validate();
            return $model;
        }
    }
}
