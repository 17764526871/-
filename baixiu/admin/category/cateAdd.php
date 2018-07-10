<?php
 include_once '../../fn.php';

 $name=$_GET['name'];
 $slug=$_GET['slug'];

 $sql = "insert into categories (name,slug) values ('$name','$slug')";

 my_exec($sql);

?>