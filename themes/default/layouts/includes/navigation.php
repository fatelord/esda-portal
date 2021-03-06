<?php
/**
 * Created by PhpStorm.
 * User: KRONOS
 * Date: 3/5/2017
 * Time: 16:48
 */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        //['label' => 'Contact', 'url' => ['/site/contact']],
        Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/site/login']]) : [
            'label' => Yii::t('app', 'Welcome') . ' (' . Yii::$app->user->identity->username . ')',
            'items' => [
                ['label' => 'My Profile', 'url' => ['//users/profile/view']],
                ['label' => 'My Documents', 'url' => ['//my-uploads']],
                ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']],
            ],
        ],
    ],
]);
NavBar::end();