<?php
// defined('PATH') OR exit('invalid path');
/**
Logger::DEBUG (100): Detailed debug information.详细的Debug信息

Logger::INFO (200): Interesting events. Examples: User logs in, SQL logs.感兴趣的事件或信息，如用户登录信息，SQL日志信息

Logger::NOTICE (250): Normal but significant events.普通但重要的事件信息

Logger::WARNING (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.

Logger::ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.

Logger::CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.

Logger::ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.

Logger::EMERGENCY (600): Emergency: system is unusable
**/
defined('LOG_PATH') OR define('LOG_PATH',dirname(__FILE__).'/../log/');

defined('DSN') OR define('DSN','mysql:host=localhost;dbname=recruit;charset=utf8');
defined('PORT') OR define('PORT',3306);
defined('DB_USER') OR define('DB_USER','root');
defined('DB_PWD') OR define('DB_PWD','root');

defined('MAIL_SERVER') OR define('MAIL_SERVER','smtp.163.com');
defined('MAIL_USER') OR define('MAIL_USER','m15116391775@163.com');
defined('MAIL_PWD') OR define('MAIL_PWD','csu0902150407');
defined('MAIL_FROM') OR define('MAIL_FROM',array('m15116391775@163.com'=>'xinda'));
defined('MAIL_TO') OR define('MAIL_TO',array('1061889812@qq.com'=>'xinda'));

defined('SALT') OR define('SALT','xinda1837');
defined('SESSION_EXPIRE_TIME') OR define('SESSION_EXPIRE_TIME','600');//s
// defined('EXCEL_TAB') or define('EXCEL_TAB',array('A1'=>'姓名','B1'=>'电话','C1'=>'申报部门','D1'=>'第一志愿','E1'=>'第二志愿','F1'=>'自我简介','G1'=>'性别','H1'=>'学院','I1'=>'班级','J1'=>'学号','K1'=>'出生年月','L1'=>'时间'));


// echo dirname(__FILE__);