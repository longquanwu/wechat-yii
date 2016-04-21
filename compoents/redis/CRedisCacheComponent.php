<?php
/**
 * CRedisCacheComponent.php
 * User: wlq
 * CreateTime: 16-4-21 下午7:55
 */
namespace app\compoents\redis;

require_once __DIR__."/src/CredisException.php";
require_once __DIR__."/src/Credis_Client.php";

use yii\base\Component;
use yii\base\Exception;

class CRedisCacheComponent extends Component{

    private $_options;  //配置文件中的options
    private $_host;  //redis地址，默认本地127.0.0.1
    private $_port;  //redis端口，默认6379
    private $_timeout;
    private $_prefix;  //key值的前缀
    /** @var  Credis_Client $_client */
    private $_client;

    public function init(){
        $this->_host = $this->_options['host'];
        $this->_port = $this->_options['port'];
        $this->_timeout = $this->_options['timeout'];
        $this->_prefix = $this->_options['prefix'];

        try{
            $this->_client = new \Credis_Client($this->_host, $this->_port, $this->_timeout);
            $this->_client->setReadTimeout(0);
        }catch (Exception $e){
            print_r($e->getMessage());
        }
    }

    /**
     * 读取配置文件中的options
     * @param $params
     */
    public function setOptions($params){
        $this->_options = $params;
    }

    /**
     * 根据ID获得对应的缓存值
     * @param string $id
     * @return mixed
     */
    public function get($id){
        $key = $this->_getKeyName($id);
        return unserialize($this->_client->get($key));
    }

    /**
     * 写入缓存
     * @param string $id
     * @param string $value
     * @param string $expire
     * @return mixed
     */
    public function set($id, $value, $expire = '3600'){
        $key = $this->_getKeyName($id);
        var_dump($this->_client);
        $result = $this->_client->set($key, serialize($value));
        if($expire){
            $this->expire($id, $expire);
        }
        return $result;
    }

    /**
     * 根据key删除缓存
     * @param string $id
     * @return mixed
     */
    public function delete($id){
        $key = $this->_getKeyName($id);
        return $this->_client->del($key);
    }

    /**
     * 设置过期时间-timestamp格式
     * @param string $id
     * @param timestamp $expireTimestamp
     * @return mixed
     */
    public function expireAt($id, $expireTimestamp){
        $key = $this->_getKeyName($id);
        return $this->_client->expireAt($key, $expireTimestamp);
    }

    /**
     * 设置过期时间-时间戳格式
     * @param $id
     * @param $expire
     * @return mixed
     */
    public function expire($id, $expire){
        $key = $this->_getKeyName($id);
        return $this->_client->expire($key, $expire);
    }

    /**
     * 生成带有前缀的key名称
     * @param string $id
     * @return string
     */
    private function _getKeyName($id){
        return $this->_prefix."-".$id;
    }

    /**
     * 清楚redis中的所有缓存
     */
    public function flush(){
        if ($this->_prefix){
            $keys = $this->_client->keys($this->_prefix);
        }else{
            $keys = $this->_client->keys("*");
        }
        foreach ((array)$keys as $k) {
            $this->_client->del($k);
        }
    }
}//end class