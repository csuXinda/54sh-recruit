<?php
namespace xinda\recruit\controller;

defined('PATH') OR exit('invalid path');

require_once dirname(__FILE__)."/../model/DB.php";
require_once dirname(__FILE__)."/../config/config.php";

use xinda\recruit\model\DB;

class Handler{

    protected $pdo;

    protected $resource;

    protected $action;

    public function __construct($action=null, $resource)
    {
        $this->action   = $action;
        $this->resource = $resource;
        $this->pdo      = DB::getInstance(DSN, DB_USER, DB_PWD);
    }

    public function getRole():int
    {
        try{
            $selectStatement = $this->pdo->select()->from('admin')->where('openid','=',$_SESSION['openid']);
            $stmt = $selectStatement->execute();
            if(false === $stmt->fetch()){
                $selectStatement = $this->pdo->select()->from('user')->where('openid','=',$_SESSION['openid']);
                $stmt = $selectStatement->execute();
                $admin = $stmt->fetch();
                if(false === $admin){
                    return -1;//others
                }else{
                    $_SESSION['role']=user;
                    return 0;//users
                }
            }else{
                $_SESSION['role']='admin';
                $_SESSION['organization'] =$admin['organization'];
                return 1;//admin
            }
        }catch(Exception $e){
           header('location:500');
        }

    }

}