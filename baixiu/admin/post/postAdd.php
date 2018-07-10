<?php
header('content-type:text/html;charset=utf-8');

include_once '../../fn.php';


$title=$_POST['title'];
$content=$_POST['content'];
$slug=$_POST['slug'];
$category=$_POST['category'];
$created=$_POST['created'];
$status=$_POST['status'];
$feature="";

//需要保存当前作者
//通过session里保存的id
session_start();
$userid=$_SESSION['user_id'];

//保存图片
$file=$_FILES['feature'];
if($file['error']===0){
    $ftmp=$file['tmp_name'];
    $name=$file['name'];
    $ext=strrchr($name,'.');
    $newname='./uploads/'.time().rand(10000,99999).$ext;
    move_uploaded_file($ftmp,'../../'.$newname);
    $feature=$newname;
}

//保存到数据库
$sql = "insert into posts (slug, title, feature, created, content, status, user_id, category_id) 
        values ('$slug', '$title', '$feature', '$created', '$content', '$status', $userid, $category)";

my_exec($sql);

 //跳转到文章页 
 header('location:../posts.php');


?>