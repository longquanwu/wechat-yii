<?php
/**
 * 微信认证
 * IndexAction.php
 * User: wlq
 * CreateTime: 16-3-18 上午11:36
 */

namespace app\actions\wechat;

use app\actions;

class IndexAction extends actions\ActionBase{

    public function invoke(){

        $request = \yii::$app->request;
        $signature = $request->get("signature");
        $timestamp = $request->get("timestamp");
        $nonce     = $request->get("nonce");
        $token = \yii::$app->params['wxtoken'];

        //验证消息来源
        if (!$this->_checkSignature($signature, $timestamp, $nonce, $token)){
            return '请通过微信访问';
        }

        //如果是第一次接入，需要返回echostr
        if ( $request->get('echostr') ){
            echo $request->get('echostr');exit;
        }

        //$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : file_get_contents('php://input');
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        file_put_contents(__DIR__."/../../runtime/wlq.log", "\n请求的数据为：".$postStr, FILE_APPEND);
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            switch($postObj->MsgType){
                case 'text':
                    $toUser         = $postObj->FromUserName;
                    $fromUser       = $postObj->ToUserName;
                    $template       = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>".time()."</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                           </xml>";
                    $content = 'WHO AM I';
                    $info = sprintf($template, $toUser, $fromUser, $content);
                    echo $info;
                    break;
            }
        }

    }

    private function _checkSignature($signature, $timestamp, $nonce, $token){

        $tmpArr = [ $token, $timestamp, $nonce ];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}//end class
