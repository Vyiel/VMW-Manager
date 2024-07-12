<?php 

require 'php_globals.php';

$sName = $GLOBALS['mysql_host'];
$uName = $GLOBALS['mysql_user'];
$pass = $GLOBALS['mysql_pass'];
$db_name = $GLOBALS['mysql_DB'];

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}