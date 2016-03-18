<?php
/**
 * 微信总控制器
 * WechatController.php
 * User: wlq
 * CreateTime: 16-3-15 下午10:45
 */
namespace app\controllers;

use app\modules\wechat\Wechat;
use yii;
use yii\web\Controller;

class WechatController extends Controller{

    public function actions(){
        return [
            'index' => 'app\actions\wechat\IndexAction',
            'msg' => 'app\actions\wechat\MsgAction',
            'events' => 'app\actions\wechat\EventsAction',
        ];
    }

}//end class