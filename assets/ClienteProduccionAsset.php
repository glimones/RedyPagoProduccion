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
class ClienteProduccionAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        // 'js/lumino.glyphs.js',
        'js/js.js',
        'https://vpayment.verifika.com/VPOS2/js/modalcomercio.js',
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAvnDL8PwDHrOivIbSY3vL4HRPVomgp0LU&sensor=true&libraries=drawing,places',
        'js/gmaps.js',
        'js/map.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
