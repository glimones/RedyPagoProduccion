<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\ClienteAsset;
use yii\helpers\Url;

ClienteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <input type="hidden" name="base_url" id="base_url" value="<?php echo Yii::getAlias('@web') ?>">
<?php $this->beginBody() ?>

<div class="wrap">
    <nav id="w0" class="navbar-inverse navbar-fixed-top navbar" role="navigation"></nav>
    <div class="container">
        <?= $content ?>
    </div>
</div>


<!-- <div class="container"> -->
    <div class="row-fluid footer hidden-xs hidden-sm">
    <div class="container">
        <div class="row-fluid">
            <!-- <div class="col-md-4"> -->
                <!-- <?php //echo Html::img( Url::to('@web/images/bankard.png'), ['class'=> 'center-block']) ?> -->
            <!-- </div> -->
            <div class="col-md-3">
                <a target="_blank" href="http://pagomedios.com/"><?php echo Html::img( Url::to('@web/images/logo-redypagos-pagos.png'), ['class'=> 'center-block logo-footer']) ?></a>
            </div>
            <div class="col-md-3">
                <?php echo Html::img( Url::to('@web/images/logo-visa.png'), ['class'=> 'center-block']) ?>
            </div>
            <div class="col-md-3">
                <?php echo Html::img( Url::to('@web/images/logo-mastercard.png'), ['class'=> 'center-block']) ?>
            </div>
            <div class="col-md-3">
                <?php echo Html::img( Url::to('@web/images/logo-alignet.png'), ['class'=> 'center-block']) ?>
            </div>
        </div>
    </div>
        <?php //echo Html::img( Url::to('@web/images/logo_payme.png'), ['class'=> 'pull-right logo-payme']) ?>
    </div>
<!-- </div> -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>