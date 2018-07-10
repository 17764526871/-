<?php
include_once '../../fn.php';
$id =$_GET['id'];

$sql="select value from options where id =10";
$str=my_query($sql)[0]['value'];
$arr=json_decode($str,true);
array_splice($arr,$id,1);
$str=json_encode($arr);
$sql="update options set value= '$str' where id=10";
my_exec($sql);



?>