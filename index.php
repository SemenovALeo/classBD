<?php
$dataDb = require 'config.php';

 class QueryBuilder
 {
     private $_db;
     private PDOStatement $stmt;
     private static $instance = null;

     private function __construct()
     {
     }

     public static function getInstance()
     {
         if (self::$instance === null){
             self::$instance = new self();
         }
         return self::$instance;
     }


     public function getConnection($dataDb)
     {
         if (!$this->_db){
             $this->_db = new PDO('mysql:host=' . $dataDb['host'] . ';dbname=' . $dataDb['database'],
                 $dataDb['user'], $dataDb['password']);
             return $this;
         }
         return $this;

     }

     // Операции над БД
     public function query($sql, $params = [])
     {
         // Подготовка запроса
         $this->stmt = $this->_db->prepare($sql);

         // Обход массива с параметрами
         // и подставление значений
         if ( !empty($params) ) {
             foreach ($params as $key => $value) {
                 $this->stmt->bindValue(":$key", $value);
             }
         }
         // Выполняем запрос
         $this->stmt->execute();
         return $this;
     }
        // вывод всех записей
     public function findAll()
     {
         return $this->stmt->fetchAll();
     }
        //вывод одной записи
     public function find()
     {
         return $this->stmt->fetch();
     }


//     public function getAll($table, $sql = '', $params = [])
//     {
//         return $this->query("SELECT * FROM $table" . $sql, $params);
//     }
//
//     public function getRow($table, $sql = '', $params = [])
//     {
//         $result = $this->query("SELECT * FROM $table" . $sql, $params);
//         return $result[0];
//     }

 }

$db = (QueryBuilder::getInstance())->getConnection($dataDb);


// Получаем и выводим данные

$post = $db->query("SELECT * FROM posts")->find();
print_r($post);
