<?php
  $number1 = rand(0,30);
  $number2 = rand(0,10);
  $result = $number1 + $number2;
 ?>
<!DOCTYPE html>
<html lang="zh">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>申请重新上传</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="./css/font-awesome.min.css">

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Bootstrap -->
    <link href="./css/mdb-p.min.css" rel="stylesheet">


</head>

<body>


    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation"><a href="./index.php">首页</a></li>
            <li role="presentation" class="active"><a href="./problem.php">申请重新上传</a></li>
            <li role="presentation"><a href="./uploaded.php">已经上传的学生列表</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">AreaLoad</h3>
      </div>


      <div class="page-header">
          <h1>申请重新上传</h1>
      </div><!--/ header -->

        <form action="request.php" method="post">
              <input type="text" name="number" placeholder="学号" required>
              <textarea input type="text" class="form-control" rows="5" name="problem" id="problem" placeholder="你遇到了什么问题需要重新上传？请简要说明" required></textarea>
              <b>请算出答案：</b><input type="text" name="captcha" placeholder="<?php echo $number1?>+ <?php echo $number2?> = ?" required>
              <input type="hidden" name="num1" value="<?php echo $number1?>"></input>
              <input type="hidden" name="num2" value="<?php echo $number2?>"></input>
              <button class="btn btn-lg btn-danger btn-block" input type="submit" onclick="click()">提交申请</button>
        </form>
        </div>
      </div>
      <?php include('footer.php') ?>
      <aside id="aside" class="sidebar col-sm-3 col-md-2 hidden-print small">
        <div class="sidebar-content">
            <div class="sidebar-body collapse in">
                <p>
                    <img src="./img/problem.jpg" style="position: fixed; bottom: -180px; left: -230px; opacity: 0.3; z-index: -100" alt="">
                </p>
            </div>
        </div>
    </aside>
    <!-- SCRIPTS -->
  <script>
		function click(){
			var str = document.getElementById("#problem").innerHTML; 
			var txt = str.replace(/'/i,"error");
			document.getElementById("#problem").innerHTML = txt;
		}
	</script>
    <!-- JQuery -->
    <script type="text/javascript" src="./js/jquery-3.1.1.min.js"></script>

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="./js/tether.min.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="./js/bootstrap.min.js"></script>

    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="./js/mdb.min.js"></script>



</body>

</html>
