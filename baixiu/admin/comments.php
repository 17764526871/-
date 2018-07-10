<?php 
  include_once '../fn.php';
  isLogin();
  // 添加页面标识
  $page = 'comments';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display:none;">
          <button class="btn btn-info btn-sm btn-approveds">批量批准</button>
          <button class="btn btn-danger btn-sm btn-dels">批量删除</button>
        </div>
        <div class="page-box pull-right">
        </div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox" class="th-chk"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>未批准</td>
            <td class="text-center">
              <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <?php include_once 'inc/aside.php'?>
  
  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/pagination/jquery.pagination.js"></script>
  <script>NProgress.done()</script>
  <script>


    $(function(){
      var state ={
        held: '待审核',
        approved: '准许',
        rejected: '拒绝',
        trashed: '回收站'
      }
      var  currentPage = 1;
      function render(page,pageSize){
        $.ajax({
        url:"./comment/comGet.php",
        data:{
          page:page ||1,
          pageSize:pageSize||10
        },
        dataType:'json',
        success:function(info){
          var obj ={
            list:info,
            state:state
          }
          var str=template('tmp',obj);
          $('tbody').html(str);
        }
      })
      }
      render();
      //分页功能
      function setPage(page){
        $.ajax({
        url:"./comment/comAllPage.php",
        dataType:'json',
        success:function(info){
          $('.page-box').pagination(info.total,{
            prev_text:"上一页",
            next_text:"下一页",
            num_edge_entries:1,
            num_display_entries: 5,
            current_page: page - 1 || 0,//默认选中页面
            load_first_page: false,
            callback:function(index) {
              render(index+1);
              currentPage=index +1;
            }
          })
        }
      })
      }
      setPage();
      
      //批准按钮
      $('tbody').on('click','.btn-approved',function(){
          var id=$(this).parent().attr('data-id');
          $.ajax({
            url:'./comment/comApproved.php',
            data: {id:id},
            success:function(){
              render(currentPage);
            }
          })
      })
      //删除功能

      $('tbody').on('click','.btn-del',function(){
        var  id=$(this).parent().attr('data-id');
        $.ajax({
          url:"./comment/comDel.php",
          data:{id:id},
          dataType:"json",
          success:function(info){
            console.log( info );
            var maxPage =Math.ceil(info.total/10);
            if (currentPage > maxPage) {
                currentPage = maxPage; //将最大的页码 赋值给 当前页面
              }
             

              render(currentPage);   
              //重新生成分页标签,选中之前默认页面
              setPage(currentPage); 
          }
        })
      })

      //全选按钮
      $(".th-chk").change(function(){
        var value=$(this).prop('checked');
        $('.tb-chk').prop('checked',value);
        //让批量按钮显示和隐藏
        if(value) {
          $(".btn-batch").show();
        }else{
          $(".btn-batch").hide();
        }

      })

      //多选功能
      $('tbody').on('change', '.tb-chk', function () {
        if($('.tb-chk').length==$('.tb-chk:checked').length){
          $('.th-chk').prop('checked', true);
        }else{
          $('.th-chk').prop('checked',false);
        }
        if ($('.tb-chk').length>0){
          $('.btn-batch').show();

        }else{
          $('.btn-batch').hide();
        }

        

      })
      //获取选中数据的id
      function getId(){
          var ids =[];
          $('.tb-chk:checked').each(function(index,ele){
            var id =$(this).parent().attr('data-id');
            ids.push(id);
          })
          return ids.join();
        }

      //批量批准
      $('.btn-approveds').click(function(){
        var ids=getId();
        $.ajax({
          url:'./comment/comApproved.php',
          data:{id:ids},
          
          success:function () {
            console.log( '批准成功' );
            render(currentPage);
          }
        })
      })
      //批量删除
      $('.btn-dels').click(function(){
        var ids=getId();
        $.ajax({
          url:"./comment/comDel.php",
          data:{id:ids},
          dataType:'json',
          success:function(info){
            var maxPage =Math.ceil(info.total/10);
            if (currentPage > maxPage) {
                currentPage = maxPage; //将最大的页码 赋值给 当前页面
              }
             

              render(currentPage);   
              //重新生成分页标签,选中之前默认页面
              setPage(currentPage); 
          }
        })
      })


    })
  </script>
  <script type="text/html" id="tmp">
  {{ each list v i }}
          <tr>
            <td class="text-center" data-id={{v.id }}><input type="checkbox" class="tb-chk"></td>
            <td>{{v.author}}</td>
            <td>{{v.content.substr(0,20) + "..." }}</td>
            <td>《{{v.title}}》</td>
            <td>{{v.created}}</td>
            <td>{{state[v.status]}}</td>
            <td class="text-right"  data-id={{v.id }}>
            {{if v.status=='held'}}
              <a href="javascript:;" class="btn btn-info btn-xs btn-approved">批准</a>
            {{/if}}
              <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
            </td>
          </tr>
  {{/each}}
  </script>
</body>
</html>
