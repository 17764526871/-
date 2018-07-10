<?php
include_once '../../fn.php';
$file=$_FILES['image'];
if($file['error']===0){
    $ftmp=$file['tmp_name'];
    $ext=strrchr($file['name'],'.');
    $newname="./uploads/".time().rand(10000,99999).$ext;
    move_uploaded_file($ftmp,'../../'.$newname);
    $info['image']=$newname;
}
    $info['text']=$_POST['text'];
    $info['link']=$_POST['link'] ;   

    $sql="select value from options where id =10";
    $str=my_query($sql)[0]['value'];
    $arr=json_decode($str,true);
    $arr[]=$info;
    $str=json_encode($arr);
    $sql="update options set value ='$str' where id=10";
    my_exec($sql);



?>