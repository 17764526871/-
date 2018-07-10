<?php
header('content-type:text/html;charset=utf-8');
    include_once '../../fn.php';
    $id =$_GET['id'];
    $sql="delete from comments where id in($id)";
    my_exec($sql);

    //因为数据变了要重新获取一次输出
    $sql1="select count(*) as total  from comments join posts on comments.post_id = posts.id";
    
    $data=my_query($sql1)[0];

    echo json_encode($data);
    

?>