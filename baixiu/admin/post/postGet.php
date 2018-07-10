<?php
 include_once '../../fn.php';
 $page=$_GET['page'];
 $pageSize=$_GET['pageSize'];

 $start=($page-1)*$pageSize;
 $sql="select posts.*, categories.name,users.nickname FROM posts 
       JOIN categories ON posts.category_id = categories.id
       JOIN users ON posts.user_id=users.id 
       ORDER BY posts.id desc
       LIMIT $start,$pageSize";

       $data=my_query($sql);
       echo json_encode($data);


?>