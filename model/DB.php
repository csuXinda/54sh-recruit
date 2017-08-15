<?php
namespace xinda\recruit\model;

defined('PATH') OR exit('invalid path');

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__).'/../config/config.php';
use Slim\PDO\Database;

class DB{

    private static $instance = null;
    private $pdo = null;
    private function __clone(){}

    private function __construct($dsn,$user,$pwd)
    {
        $this->pdo = new Database($dsn, $user, $pwd);
        // return $this->pdo;
    } 

    public static function getInstance($dsn,$user,$pwd)
    {


        if(null === self::$instance){
            self::$instance = new self($dsn,$user,$pwd);
        }
        return self::$instance;
    }


    public function select(array $columns = array('*'))
    {
        return $this->pdo->select($columns);
    }

    public function insert(array $columnsOrPairs = array())
    {
        return $this->pdo->insert($columnsOrPairs);
    }

    public function update(array $pairs = array())
    {
        return $this->pdo->update($pairs);
    }

    public function delete($table = null)
    {
        return $this->pdo->delete($table);
    }
}

// $pdo  = DB::getInstance(DSN,DB_USER,DB_PWD);

// // $pdo = new Database(DSN,DB_USER,DB_PWD);


// $selectStatement = $pdo->select(array('AVG(age)'))->from('employee');
// $selectStatement->where('age','=', 20)->where('sex','=','m' );
// echo $selectStatement;
// $insertStatement = $pdo->insert(array(0=>'id', 1=>'usr',2=>'pwd'))
//                        ->into('users')
//                        ->values(array(1234, 'your_username', 'your_password'));



// echo $insertStatement;
// $stmt = $selectStatement->execute();
// $data = $stmt->fetchall();
// var_dump($data);  

