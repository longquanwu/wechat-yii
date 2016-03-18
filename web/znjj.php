<?php
    header("content-type:text/html;charset=utf-8");
    if ($_POST['pc']){
        run();
    }
    
    function run(){
        mysql_connect('127.0.0.1:3306', 'root', 'w314314') or die("数据库连接失败");
        mysql_select_db('wlq');
        if (mysql_query("update znjj set status = 1 where name = 'pc'")){
            echo '您的电脑即将关机';
        }else{
            echo '操作失败请刷新重试';
        }
    }
?>
<form action='' method='post'>
    <input type='hidden' name='pc' value='1'>
    <input type='submit' value='关机'>
</form>
