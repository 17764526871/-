<?php 
include_once '../fn.php';
isLogin();
  // 添加页面标识
$page = 'posts';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="../assets/vendors/pagination/pagination.css">
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li>
        </ul> -->
        <div class="page-box pull-right"></div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>随便一个名称</td>
            <td>小小</td>
            <td>潮科技</td>
            <td class="text-center">2016/10/07</td>
            <td class="text-center">已发布</td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
        </tbody>
      </table>
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
          <li class="active"><a href="posts.html">所有文章</a></li>
          <li><a href="post-add.html">写文章</a></li>
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
  <!-- 引入侧边栏 -->
  <?php include_once './inc/aside.php' ?>
  <!-- 引入模态框 -->
  <?php include_once './inc/edit.php' ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/pagination/jquery.pagination.js"></script>
  <script src="../assets/vendors/wangEditor/wangEditor.min.js"></script>
  <script src="../assets/vendors/moment/moment.js"></script>
  <script>NProgress.done()</script>
  <script>
    $(function () {  
       //草稿（drafted）/ 已发布（published）/ 回收站（trashed）
       var state = {
         drafted: '草稿',
         published: '已发布',
         trashed: '回收站'
       }
       var currentPage=1;
        function render(page,pageSize){
          $.ajax({
            url:'./post/postGet.php',
            data:{
              page:page||1,
              pageSize:pageSize||10
            },
            dataType:'json',
            success:function(info){
              //根据数据渲染模板
              var obj={
                list:info,
                state:state
              }
              var html1=template('tmp',obj);
              $('tbody').html(html1);
            }




          })
        }
        render();
        function setPage(page){
          $.ajax({
            url:'./post/postAllPage.php',
            dataType:'json',
            success:function(info){
             //根据数据总数生成分页标签
             $('.page-box').pagination(info.total,{
              prev_text: '<',
                next_text: '>',
                num_display_entries: 5, //连续主体格式
                current_page: page - 1 || 0, //默认选中页码
                num_edge_entries:1, 
                load_first_page: false, //页面刷新是 callback不执行
                callback: function (index) { // 当前被点击页码的索引值 
                    //渲染当前页
                    render(index + 1);
                    //更新当前页
                    currentPage = index + 1;
                }
             
             
             })
            }



          })
        }
        setPage();

        //删除文章
        $('tbody').on('click','.btn-del',function(){
          var id=$(this).parent().attr('data-id');
          
          $.ajax({
            url:'./post/postDel.php',
            data:{id:id},
            dataType:'json',
            success:function(info){
              var maxPage=Math.ceil(info.total/10);
              currentPage=currentPage>maxPage ? maxPage:currentPage;
              
              render(currentPage);
              setPage(currentPage); 
            }


            })
        })
           
        //模态框先设置好
          // 1. 在模态框中准备基本的数据，
          //   - 填充分类下拉列表
          $.ajax({
            url:'./category/cateGet.php',
            dataType:'json',
            success: function(info) {  
                //渲染分类数据
                $('#category').html( template('tmp-cate', {list: info}) );
            }
          })
          //   - 填充状态列表的
          var str =template('tmp-state',{obj:state});
          $('#status').html(str);
          //   - 准备富文本编辑器
          var E=window.wangEditor;
          var editor=new E ('#content-box');
          editor.customConfig.onchange=function(html){
            $('#content').html(html);
          }
          editor.create();
          //   - 别名同步
          $("#slug").on('input',function(){
            $('#strong').text($(this).val()||slug);
          })
          //   - 本地预览
          $('#feature').change(function(){
            var file=this.files[0];
            var url=URL.createObjectURL(file);
            $('#img').attr('src',url).show();
          })

          //   - 时间格式化
          $('#created').val(moment().format('YYYY-MM-DDTHH:mm'));
          
          // 2. 点击按钮，获取当前点击的文章id，去后台获取当前文章的数据
          // 3. 把数据填充在模态框的各个表单中(注意保持文章的id，根据id进行修改)
          $("tbody").on('click','.btn-edit',function(){
            var id=$(this).parent().attr('data-id');
            $.ajax({
              url:'./post/postEdit.php',
              data:{
                id:id
              },
              dataType:"json",
              success:function(info){
                  console.log( info );
                  $('.edit-box').show();
                  //   - 标题
                  $('#title').val(info.title);
                  //   - 别名(strong标签页修改)
                  $('#slug').val(info.slug);
                  $('#strong').text(info.slug);
                  //   - 图像（用img标签显示）
                  $("#img").attr('src','../'+ info.feature).show();
                  //   - 分类选中(selected)
                  $('#category option[value=' + info.category_id + ']').prop('selected', true);
                  //   - 状态选中(selected)
                  $('#status option[value='+info.status +']').prop('selected', true)
                  //   - 时间设置(注意格式)
                  $('#created').val(moment().format('YYYY-MM-DDTHH:mm'));
                  //   - 文章内容设置(同时设置textarea  和 富文本编辑器 )
                  editor.txt.html(info.content);
                  $('#content').val(info.content)
                  //   - 设置id
                  $('#id').val(info.id);
              }
            })
          })
          



        //点击保存更新数据到数据库
        $('.btn-update').click(function(){
           //获取表单的数据
           var fromData = new FormData( $('#editForm')[0] );
          //  console.log(formData);
          $.ajax({
            type: 'post',
            url: './post/postUpdate.php',
            data: fromData, 
            contentType: false,  
            processData: false,  //不需要对数据进行编码
            success: function (info) {
              console.log(info);  
              //更新完成后 关闭模态框
              $('.edit-box').hide();
              //重新渲染当前页         
              render(currentPage);   
            }
          })
        })




              //8-放弃功能
        $('.btn-cancel').click(function () {
          //隐藏模态框
          $('.edit-box').hide();
        })

    })
  
  
  
  </script>

  <script type="text/html" id="tmp">
        {{each list v i}}
           <tr>
            <td class="text-center" data-id={{v.id}}><input type="checkbox"></td>
            <td>{{v.title}}</td>
            <td>{{v.nickname}}</td>
            <td>{{v.name}}</td>
            <td class="text-center">{{v.created}}</td>
            <td class="text-center">{{state[v.status]}}</td>
            <td class="text-center" data-id={{v.id}}>
              <a href="javascript:;" class="btn btn-default btn-xs btn-edit">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
            </td>
          </tr>
        {{/each}}
  </script>
  <script type="text/template" id="tmp-cate">
          <!-- 分类模板 -->
          {{each list v i}}
              <option value="{{v.id}}">{{v.name}}</option>
          {{/each}}
  </script>

  <script type="text/template" id="tmp-state">
          <!-- 状态模板 -->
          {{each obj v i}}
              <option value="{{i}}">{{v}}</option>
          {{/each}}
  </script>

</body>
</html>
