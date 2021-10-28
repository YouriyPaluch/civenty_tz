<?php
include_once 'config.php';
include_once 'functions.php';
include_once 'User.php';
$users = new \Civenty\User();

$users->addUser($user);
//$number=[
//    'countryCode' =>380,
//    'operatorCode' =>50,
//    'number'=> 7654321
//];
//var_dump($users->topUpPhone($number, 1));
//$users->deleteUser(1934);
//380-50-7654321