<?php

namespace app\modules\wx\controllers;

use yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    private $token;

    public function init(){
        parent::init();
        $this->token = Yii::$app->params['wxtoken'];
    }

    public function actionIndex()
    {
        //先验证访问来源
        $this->checkweixin() or die("请通过微信公众号访问");

        //第一次验证需要ECHO
        if ( ($echostr = Yii::$app->request->get('echostr')) !== '' )
            echo $echostr;exit;

        $this->execute();

        return $this->render('index');
    }

    //验证微信TOKEN
    private function checkweixin(){
        $request = Yii::$app->request;
        $signature = $request->get("signature");
        $timestamp = $request->get("timestamp");
        $nonce     = $request->get("nonce");

        $tmpArr = [ $this->token, $timestamp, $nonce ];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    private function execute(){
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        $postObj = simplexml_load_string( $postArr );
        $this->printfLog($postArr);
        switch ( $postObj->MsgType ){
            case 'text':
                $textobj = new TextController();
                $content = $textobj->execute($postObj->Content);
                break;
            case 'event':
                $eventobj = new EventController();
                $content = $eventobj->execute($postObj->Event, $postObj->EventKey);
                break;
        }

        if ( $postObj->Latitude && $postObj->Longitude ){
            $content = $this->location($postObj->Latitude, $postObj->Longitude);
        }
        $model = new ResponModel();
        $model->response($postObj, $content);
    }

    private function printfLog($inputArr){
        $content = date('[Y-m-d  H:i:s]  ')."\n".var_export($inputArr, true)."\n\n";
        $logpath = './Application/Home/Log/'.date('Y-m-d');
        file_put_contents($logpath, $content, FILE_APPEND);
    }

}//end class
