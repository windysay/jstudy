<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BackendAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';   // public $jsOptions = ['position' => \yii\web\View::POS_HEAD];所有js都加载在最前面
    
    public $css = [
        'css/backend_basic.css',
    	'css/basic.css',
        '../vendor/fontawesome/font-awesome.min.css',//Awesome字体引入
    ];
    public $js = [
    	'js/tool.js',
	    'js/bootstrap.min.js',
    	'js/bootstrap-hover-dropdown.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
