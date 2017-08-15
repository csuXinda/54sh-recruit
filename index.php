<?php
/**
 * @author xinda 
 * @date 2017/08/07
 * @time 19:00
 */
namespace xinda\recruit;

define('PATH', $_SERVER['DOCUMENT_ROOT']);

// echo PATH;
// exit();
require_once "./model/Log.php";
require_once "./config/config.php";
// require_once "./vendor/autoload.php";

use xinda\recruit\model\Log;



$log = new Log('indexChannel');


//Note that few, if any, of these will be available (or indeed have any meaning) if running PHP on the command line.

/**
 * 微信处理code，session标识身份，认证身份
 */
#get只含资源，put由动作和资源组成
#recruit.54sher.com/insert/user (put)(注册个人)
#recruit.54sher.com/insert/resume (put)(注册意愿)
#recruit.54sher.com/update/user (put)（更改user信息）
#recruit.54sher.com/login(获得个人信息)
#recruit.54sher.com/manage(获得本组织所有信息)
#recruit.54sher.com/excel(下载excel数据表)

// var_dump($_GET);
// var_dump($_SERVER['SERVER_NAME']);
// var_dump($_SERVER['REMOTE_ADDR']);
// var_dump($_SERVER['HTTP_REFERER']);

/**
 * code获取
 * openid获取
 * session设置
 */



ini_set('session.use_only_cookies',true);//只允许通过cookie传递会话id
// ini_set('display_errors','off' );
// ini_set('log_errors','on');

session_set_cookie_params(SESSION_EXPIRE_TIME);
session_start();


/**
 * 防止会话劫持
 * @var [type]
 */
$token = md5(strval(date('h').SALT));

/**
 * $_REQUEST默认情况下包含了 $_GET，$_POST 和 $_COOKIE 的数组 ,用于收集HTML表单提交的数据。
 */
if(!isset($_REQUEST['token']) or $_REQUEST['token'] != $token){
    http_response_code(405);
    exit();
}

$_SESSION['token'] = $token;
output_add_rewrite_var('token', $token);//相当于在表单里添加了一个hidden的key => value


/**
 * 防止固定会话攻击
 */
if(!isset($_SESSION['generated']) or $_SESSION['generated']<(time()-30)){
    session_regenerate_id();
    $_SESSION['generated'] = time();
}


//设置session
if(!isset($_SESSION['openid'])){
    $openid = '';//通过code获取openid
    if("error" == $openid||empty($openid)){
        http_response_code(405);
        exit();
    }
    $_SESSION['openid'] = $openid;
}



// var_dump($_SERVER['REQUEST_METHOD']);
// var_dump($_SERVER['PATH_INFO']);
if(!isset($_SERVER['PATH_INFO']) OR !isset($_SERVER['REQUEST_METHOD'])){
    http_response_code(405);
    exit();
}
$request = explode('/', $_SERVER['PATH_INFO']);
// var_dump($request);
$request = array_filter($request);


$resource = array_pop($request);
$action = array_pop($request);
if(empty($resource)){
    http_response_code(404);
    exit();
}


// echo $resource,PHP_EOL;
//只处理合法资源
$resources = ['user','resume','login','manage','excel'];//available /put to the config
if(!in_array($resource, $resources)){
    http_response_code(404);
    exit();
}

$admin = ['manage','excel'];


$method = strtolower($_SERVER['REQUEST_METHOD']);
switch ($method) {
    case 'get':
        echo "get",PHP_EOL;
        require_once "./controller/GetHandler.php";
        $handler = new \xinda\recruit\controller\GetHandler($action,$resource);
        if(in_array($resource,$admin)){
        // echo 402,PHP_EOL;
            if(isset($_SESSION['role']) and 'admin' === $_SESSION['role']){
                call_user_func(array($handler, $resource));
            }else{
                if(1 === $handler->getRole()){
                    call_user_func(array($handler, $resource));
                }else{
                    http_response_code(403);
                    exit();
                }
            }

        }else{
            if(method_exists($handler,$resource)){
                echo 403,PHP_EOL;
                // echo $handler->call_user_func($resource);  
                call_user_func(array($handler, $resource));
  
            }else{
                // var_dump(get_defined_functions()); 
                echo  404,PHP_EOL;
                // echo $resource;
                http_response_code(404);
                exit();
            }
            
        }

        break;
    case 'put':
        require_once "./controller/GetHandler.php";
        $handler = new \xinda\recruit\controller\GetHandler($action,$resource);
        // $handler->call_user_func($resource);
        $handler->put();

    default:
        # code...
        http_response_code(405);
}
