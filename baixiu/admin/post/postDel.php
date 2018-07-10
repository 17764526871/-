<?php
header('content-type:text/html;charset=utf-8');
      include_once '../../fn.php';
      $id = $_GET['id'];
      $sql="delete from posts where id=$id ";
      my_exec($sql);
      //删除完需要重新获取一次数据返回有效的文章总数
      $sql="select count(*) AS total FROM posts
            JOIN users ON posts.user_id = users.id
            JOIN categories ON posts.category_id = categories.id";
      $data=my_query($sql)[0];
      echo json_encode($data)




?>