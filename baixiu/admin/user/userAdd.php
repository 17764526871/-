<?php
include_once "../../fn.php";

$email=$_POST['email'];
$slug=$_POST['slug'];
$nickname=$_POST['nickname'];
$password=$_POST['password'];


$sql="insert into users (slug,email,password,nickname) value ( '$slug','$email',$password,'$nickname' )";
my_exec($sql);

?>