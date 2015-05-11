<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;

class SiteController extends Controller
{   
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }     
}
