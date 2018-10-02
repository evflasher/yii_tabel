<?php
namespace app\widgets;

use yii\base\BootstrapInterface;
use yii\base\Application;

class InitBootstrapParams implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->params['appRegionId'] = $_COOKIE[$app->params['regionCookieName']];
    }
}