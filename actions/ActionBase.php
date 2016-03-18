<?php
/**
 * 所有action都需要继承的父类
 * ActionBase.php
 * User: wlq
 * CreateTime: 16-3-18 上午11:07
 */

namespace app\actions;

use yii\base\Action;

abstract class ActionBase extends Action{

    //子类处理逻辑
    abstract protected function invoke();

    //Action 必须实现的run方法
    public function run(){

        $res = $this->invoke();

        $this->_display( $res );

    }


    //处理返回结果
    private function _display( $res ){
        echo $res;
    }


}