<?php 
namespace xinda\recruit\controller;

defined('PATH') OR exit('invalid path');

require_once dirname(__FILE__)."/../model/DB.php";
require_once dirname(__FILE__)."/../model/Log.php";
require_once dirname(__FILE__)."/../config/config.php";
require_once dirname(__FILE__)."/Handler.php";
use xinda\recruit\model\DB;
use xinda\recruit\model\Log;
use xinda\recruit\controller\Handler;  

class GetHandler extends Handler{

    // protected $resource;

    // protected $action;

    protected $openid;

    protected $dbArray;

    protected $filterPost = array();

    protected $log;

    // protected $pdo;

    public function construct($action=null,$resource)
    {
        
        parent::contruct($action,$resource);

        $this->log      = new Log('GetChannel');

        // $this->action   = $action;
        // $this->resource = $resource;


        // $this->pdo      = DB::getInstance(DSN,DB_USER,DB_PWD);

    }

    private function ajaxResponse($status=1,$message=null,$type=1)
    {
        $this->response['status']=$status;
        $this->response['message']=$message ?? '操作成功';
        if(1===$type){
            return json_encode($this->response);
        }else{
            return $this->response;
        }
    }


    public function login($openid = null)
    {
        //if expire 
        try{
            $selectStatement = $this->pdo->select()->from('user')->where('openid','=',$_SESSION['openid']);
            $stmt = $selectStatement->execute();
            $user = $stmt->fetch();

            $selectStatement = $this->pdo->select()->from('resume')->where('openid','=',$_SESSION['openid']);
            $stmt = $selectStatement->execute();
            $resume = $stmt->fetchall();//array

        }catch(\PDOException $e){   
            header('location:404');
        }

        if(false === $user){
            header('location:');//填报个人信息
        }elseif(empty($resume)){
            header('location:');//填写志愿信息
        }else{
            header('location:');
            print(json_encode(array_merge($data)));//合并并且返回
        }
        
    }

    public function manage()
    {
        
        if(!isset($_SESSION['role']) or !isset($_SESSION['organization']) or 'admin' !== $_SESSION['role'] or empty($_SESSION['organization'])){
                header('location:403forbidden');
        }
        try{
            $selectStatement = $this->pdo->select()->from('resume')->where('organization','=',$_SESSION['organization']); 
            $stmt = $selectStatement->execute();
            $resume = $stmt->fetchall();//array
        }catch(\PDOException $e){
            header('location:500');
        }

        header('location:');
        print(json_encode($resume));

    }


    public function excel()
    {
        if(!isset($_SESSION['role']) or !isset($_SESSION['organization']) or 'admin' !== $_SESSION['role'] or empty($_SESSION['organization'])){
                header('location:403forbidden');
        }
        try{
            $selectStatement = $this->pdo->select()->from('user')->where('organization','=',$_SESSION['organization']);
            $stmt = $selectStatement->execute();
            $user = $stmt->fetchall();

            $selectStatement = $this->pdo->select()->from('resume')->where('organization','=',$_SESSION['organization']);
            $stmt = $selectStatement->execute();
            $resume = $stmt->fetchall();//array

        }catch(\PDOException $e){   
            header('location:500');
        }     

        if(empty($user) or empty($resume)){
            header('location:anavaliable');
        }
        require_once "../model/Excel.php";

        try{
            $excel = new xinda\recruit\model\Excel.php($_SESSION['organization'],array_merge($user,$resume));
        }catch(\Exception $e){
            header('location:unavaliable');
        }
        
    }

    // private function is_admin()
    // {

    //     $selectStatement = $this->pdo->select()->from('admin')->where('openid','=',$_SESSION['openid']);
    //     $stmt = $selectStatement->execute();
    //     return $stmt->fetch();
    // }

    // private function is_register()
    // {

    //     $selectStatement = $this->pdo->select()->from('admin')->where('openid','=',$_SESSION['openid']);
    //     $stmt = $selectStatement->execute();
    //     return $stmt->fetch();
    // }


    public function filter()
    {
        $args = array(
                    'product_id'   => FILTER_SANITIZE_ENCODED,//Sanitizing过滤器
                    'component'    => array('filter'    => FILTER_VALIDATE_INT,//
                                            'flags'     => FILTER_REQUIRE_ARRAY, 
                                            'options'   => array('min_range' => 1, 'max_range' => 10)
                                        ),
                    'versions'     => FILTER_SANITIZE_ENCODED,
                    'doesnotexist' => FILTER_VALIDATE_INT,
                    'testscalar'   => array(
                                            'filter' => FILTER_VALIDATE_INT,
                                            'flags'  => FILTER_REQUIRE_SCALAR,
                                        ),
                    'testarray'    => array(
                                            'filter' => FILTER_VALIDATE_INT,
                                            'flags'  => FILTER_REQUIRE_ARRAY,
                                        )

        );

        $myinputs = filter_input_array(INPUT_POST, $args);
    }

}