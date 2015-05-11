<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\base\Model;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;

use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;

use app\models\User;
use app\models\AnswerWrong;

class AnswerController extends Controller
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
                'wrong' => ['POST', 'OPTIONS'],
            ],   
        ];

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                QueryParamAuth::className(),
            ],
        ];

        return $behaviors;
    }

    public function actionWrong()
    {
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            return true;
        }

        $errors = [];
        $answers = Yii::$app->request->post('AnswersWrong');

        foreach($answers['answers'] as $ans_id => $answer) {
            $modelArray[] = new AnswerWrong();
            $assign['AnswerWrong'][] = [
                'word_id' => $answer['id'],
                'user_id' => Yii::$app->user->id,
                'answer' => $answer['answer'],
                'type' => $answer['type']
            ];
        }

        if (Model::loadMultiple($modelArray, $assign) && Model::validateMultiple($modelArray)) {
            $errors = false;
            foreach ($modelArray as $model_answer) {
                if(!$model_answer->save())
                    $errors[] = Html::errorSummary($model_answer);  
            }
            if($errors)
                return $errors;
        } else{
            return 'No record answers';
        }
    }
}
