<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AngularCoreAsset extends AssetBundle
{
    public $baseUrl = '@web';
    
    public $js = [
        'https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js'
    ];
}
