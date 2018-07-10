<?php 
    // 数据库ip 用户名 密码 数据库名
define('HOST', '127.0.0.1');
define('UNAME', 'root');
define('PWD', 'root');
define('DB', 'z_baixiu');

    //封装增删改语句
function my_exec($sql)
{

        // 1.链接数据库
    $link = @mysqli_connect(HOST, UNAME, PWD, DB);
    if (!$link) {
        echo '数据库链接失败';
        return false;
    }
    // 2.准备语句
    // 3.执行语句,并分析结果
    if (!mysqli_query($link, $sql)) {
        echo '操作失败!';
        mysqli_close($link);
        return false;
    }
    //成功
    mysqli_close($link);
    return true;
    // 4.断开数据库链接
}
    


    //封装查语句
    function my_query($sql) {
        //1-连接数据库
        $link = @mysqli_connect(HOST, UNAME, PWD, DB);
        if (!$link) {
            echo '数据库连接失败';
            return false;
        }
 
        //2-执行
        $res = mysqli_query($link, $sql);
 
        //判断是否有数据，没有数据的话，程序到此结束
        // $res == false  结果集的行数为0 
        if (!$res || mysqli_num_rows($res) == 0 ) {
            echo '未获取到数据！';
            mysqli_close($link);
            return false;
        }
 
        //有数据处理数据
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
 
        mysqli_close($link);
        return $data; //以二维数组形式返回数据
     }




    //封装用户登录检测函数
function islogin()
{
    if (!empty($_COOKIE['PHPSESSID'])) {

        session_start();
        if (isset($_SESSION['user_id'])) {
            $msg= $_SESSION['user_id'];
        } else {
            header('location: ./login.php');
            die();
        }
    } else {
        header('location: ./login.php');
        die();
    }
}


?>