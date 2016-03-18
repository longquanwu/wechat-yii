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
        if (!$this->_checkSignature($signature, $timestamp, $nonce, $token)){
            return '请通过微信端访问';
        }
        if ( empty($request->get('echostr')) ){
            echo $request->get('echostr');exit;
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

}
