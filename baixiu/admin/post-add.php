<?php 
  include_once '../fn.php';
  isLogin();
  // 添加页面标识
  $page = 'post-add';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="users.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="loginOut.php"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" action="./post/postAdd.php" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">标题</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容" style="display:none;"></textarea>
            <div id="content-box"></div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong id="strong">slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail " style="display: none ;width: 80px; height: 80px;" id="img" >
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <option value="1">未分类</option>
              
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- <div class="aside">
    <div class="profile">
      <img class="avatar" src="../uploads/avatar.jpg">
      <h3 class="name">布头儿</h3>
    </div>
    <ul class="nav">
      <li>
        <a href="index.html"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <li class="active">
        <a href="#menu-posts" data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse in">
          <li><a href="posts.html">所有文章</a></li>
          <li class="active"><a href="post-add.html">写文章</a></li>
          <li><a href="categories.html">分类目录</a></li>
        </ul>
      </li>
      <li>
        <a href="comments.html"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li>
        <a href="users.html"><i class="fa fa-users"></i>用户</a>
      </li>
      <li>
        <a href="#menu-settings" class="collapsed" data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse">
          <li><a href="nav-menus.html">导航菜单</a></li>
          <li><a href="slides.html">图片轮播</a></li>
          <li><a href="settings.html">网站设置</a></li>
        </ul>
      </li>
    </ul>
  </div> -->
  <?php include_once './inc/aside.php'?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/wangEditor/wangEditor.min.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <!-- 引入时间格式化插件 -->
  <script src="../assets/vendors/moment/moment.js"></script>
  <script>NProgress.done()</script>
  <script>
    $(function() {
      //一,设置添加页面

        // 1. 分类下拉数据填充
        $.ajax({
          url:'./category/cateGet.php',
          dataType:'json',
          success:function(info){
            $('#category').html(template('tmp-cate',{list:info}));
          }


        })

        // 2. 状态下拉数据填充
        var state = {
          drafted: '草稿',
          published: '已发布',
          trashed: '回收站'
        }
        var html1=template('tmp-state',{obj:state});
        $('#status').html(html1);

        // 3. 别名同步
          $('#slug').on('input',function(){
            $('#strong').text($(this).val()||'slug');
          })

        // 4. 默认时间设置
          $('#created').val(moment().format('YYYY-MM-DDTHH:mm'));

        // 5. 图片本地预览
        $('#feature').change(function(){
          var file=this.files[0];
          var url =URL.createObjectURL(file);
          $('#img').attr('src',url).show();
        })
        // 6. 富文本编辑器的使用
        var E =window.wangEditor;
        var editor=new E('#content-box');
        editor.customConfig.onchange=function (html){ 
            $('#content').val(html);
         }
        editor.create();
        var a =document.querySelector("");




      //二,提交数据到服务器
    })
  
  
  
  </script>
  <script type="text/html" id="tmp-cate">
   <!-- 分类模板 -->
   {{each list v i}}
   <option value="{{v.id}}">{{v.name}}</option>
   {{/each}}
  </script>

  <script type="text/html" id="tmp-state">
    <!-- 状态模板 -->
    {{each obj v i}}
    <option value="{{i}}">{{v}}</option>
    {{/each}}
  </script>

</body>
</html>
