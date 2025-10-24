<?php
try{

$pdo = new PDO("mysql:host=localhost;port=3307;dbname=wheels_of_fortune_db",username: "root", password: "528_hloni");
}catch (PDOException $e){
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

?>