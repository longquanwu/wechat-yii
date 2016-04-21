<?php
/**
 * IndexController.php
 * User: wlq
 * CreateTime: 16-3-16 下午5:35
 */
namespace app\controllers;

class IndexController extends \yii\web\Controller{
    public function actionIndex(){
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : file_get_contents('php://input');
        //简单的日志测试
        file_put_contents(__DIR__."/../runtime/post.log", "\n\n".date("Y-m-d H:i:s")."请求URL：wx.longquangege.cn{$_SERVER['REQUEST_URI']}\n POST请求的数据为：".$postStr, FILE_APPEND);
        echo 'index/index';
    }

    public function actionTest(){
//        \Yii::$app->cache->get(1);
        print_r(\Yii::$app->cache->set('wlq', 'whoami', 3600));
    }
}
