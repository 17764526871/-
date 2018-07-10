<?php 
header('content-type:text/html;charset=utf-8');
    include_once '../../fn.php';
    $page =$_GET['page'];
    $pageSize=$_GET['pageSize'];
    
    $start =($page -1)*$pageSize;

    $sql = "select comments.*, posts.title  from comments  
            join posts on comments.post_id = posts.id   
            limit $start, $pageSize";

    $data=my_query($sql);

    echo json_encode($data);

    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';





?>