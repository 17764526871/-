<?php
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// echo '<pre>';
// print_r($_FILES);
// echo '</pre>';

include_once '../../fn.php';

//获取数据
//1-获取前端修改后 数据 和图片
$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
$slug = $_POST['slug'];
$category = $_POST['category'];
$created = $_POST['created'];
$status = $_POST['status'];
$feature = ''; //默认为空

//如果是上次的图片就保存图片到服务器
$file=$_FILES['feature'];
if($file['error']===0){
    $ftmp=$file['tmp_name'];
    $ext=strrchr($file['name'],'.');
    $newname='uploads/'.time().rand(10000,99999).$ext;
    move_uploaded_file($ftmp,'../../'.$newname);
    $feature=$newname;
}
$sql="update posts set title= '$title', content='$content', 
slug='$slug', category_id='$category', created='$created', 
status='$status',feature='$feature' where id=$id";

// if(empty($feature)){
//     $sql="update posts set title= '$title', content='$content', slug='$slug', category_id='$cateid', created='$created', status='$status' where id=$id";
// }else{
//     $sql="update posts set title= '$title', content='$content', slug='$slug', category_id='$cateid', created='$created', status='$status',feature='$feature' where id=$id";
// }
//执行
my_exec($sql);  

?>