<?php

namespace xinda\recruit\model;

defined('PATH') OR exit('invalid path');

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__).'/../config/config.php';
// require_once('../vendor/swiftmailer/swiftmailer/lib/swift_required.php');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Handler\ErrorLogHandler; 
use Monolog\Formatter\JsonFormatter;
use Monolog\ErrorHandler;




class Log{

    protected $logger;

    public function __construct($channel,$file='default.txt')
    {

        date_default_timezone_set("Asia/Shanghai");

        $this->logger = new Logger($channel);
        $stream_handler = new StreamHandler(LOG_PATH.$file,Logger::NOTICE);
        $stream_handler->setFormatter(new JsonFormatter());
        $this->logger->pushHandler($stream_handler);
       
        $transport = (new \Swift_SmtpTransport(MAIL_SERVER, 25))
                    ->setUsername(MAIL_USER)
                    ->setPassword(MAIL_PWD);

        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Website error!'))
                    ->setFrom(MAIL_FROM)
                    ->setTo(MAIL_TO);

        // $this->logger->pushHandler(new SwiftMailerHandler($mailer,$message,Logger::CRITICAL));
        $this->logger->pushProcessor(new MemoryUsageProcessor);
        ErrorHandler::register($this->logger);
    }


}

// $demo = new Log('first','first.txt');

// $a = 1/0;