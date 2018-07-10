<?php
include_once '../../fn.php';
$sql="select count(*) AS total FROM posts
      JOIN users ON posts.user_id = users.id
      JOIN categories ON posts.category_id = categories.id";
$data=my_query($sql)[0];
echo json_encode($data)

?>