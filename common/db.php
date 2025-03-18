<?php

try{
    $pdo = new PDO("mysql: host=localhost; dbname=notepad_db", "root", "");
}catch(PDOException $exception){
    echo $exception->getMessage();
}



?>