<?php
session_start();
//Check for SESSION
if(!$_SESSION['valid'])
{
	header("Location:./login.php");
	exit();
}

$connect = new PDO('sqlite:./db/db.sqlite');
$array = $connect->query("SELECT * FROM course;");
$tickets = $connect->query("SELECT * FROM problem");
$trustlists = $connect->query("SELECT * FROM trustlist");
?>
<!DOCTYPE html>
<html lang="zh">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>AreaLoad | 管理面板</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="./css/font-awesome.min.css">

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Bootstrap -->
    <link href="./css/mdb.min.css" rel="stylesheet">
</head>

<body>
<img src="./img/admin.jpeg" style="position: fixed;width: 100%; opacity: 0.2; z-index: -100" alt="">
<?php include('./partial/nav.php') ?>
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-12">
			  <div class="panel panel-success">
			    <div class="panel-heading">课程管理</div>
			    <div class="panel-body">
              <ul class="list-group">

              <?php
              foreach ($array as $course) {
				$eachcoursename = $course['name'];
                $eachcourseid = $course['id'];
				$eachcoursecategory = $course['category'];
				$eachcoursecategoryid = $course['categoryid'];
				$counts = $connect->query("SELECT COUNT(*) FROM uploaded WHERE course = '$eachcourseid';");
                $count = $counts->fetchColumn();
                echo "<li class=\"list-group-item\">";
                echo "<span class=\"badge\">". $count . "</span>";

								echo "<b>" . $eachcoursename . "</b>" . " | " . $eachcourseid . " | " . $eachcoursecategory . " | " . "$eachcoursecategoryid";
								echo "<form action=\"course.php\" method=\"post\">";
								echo "<input type=\"hidden\" name=\"rstcourseid\" value=\"$eachcourseid\"></input>";
								echo "<input type=\"hidden\" name=\"rstcoursecategoryid\" value=\"$eachcoursecategoryid\"></input>";
		    					echo "<button class=\"badge\" input type=\"submit\">重置</button>";
								echo "</form>";
								echo "<form action=\"edit.php\" method=\"post\">";
								echo "<input type=\"hidden\" name=\"editcourseid\" value=\"$eachcourseid\"></input>";
		    					echo "<button class=\"badge\" input type=\"submit\">修改</button>";
								echo "</form>";
								echo "<form action=\"course.php\" method=\"post\">";
								echo "<input type=\"hidden\" name=\"delcourseid\" value=\"$eachcourseid\"></input>";
								echo "<button class=\"badge\" input type=\"submit\">删除</button>";
								echo "</form>";

                echo "</li>";
              }
                ?>

            </ul>
		</div>
	</div>

			<div class="panel panel-success">
              <div class="panel-heading">分类管理</div>
              <div class="panel-body">
				  <?php
				  $categoryarray = $connect->query("SELECT DISTINCT category,categoryid FROM course;");
				  foreach ($categoryarray as $category) {
					$eachcoursecategory = $category['category'];
					$eachcoursecategoryid = $category['categoryid'];
	                echo "<li class=\"list-group-item\">";

									echo "<b>" . $eachcoursecategory . "</b>" . " | " . "$eachcoursecategoryid";
									echo "<form action=\"course.php\" method=\"post\">";
									echo "<input type=\"hidden\" name=\"delcategoryid\" value=\"$eachcoursecategoryid\"></input>";
									echo "<button class=\"badge\" input type=\"submit\">删除</button>";
									echo "</form>";

	                echo "</li>";
	              }
				  ?>
              </div>
            </div>

            <div class="panel panel-success">
              <div class="panel-heading">课程管理</div>
              <div class="panel-body">
			  <h2>添加课程</h2>
              <form action="course.php" method="post">
              <input type="text" name="addcoursename" class="form-control" placeholder="课程名称（例：Web技术基础）" required>
              <input type="text" name="addcourseid" class="form-control" placeholder="课程ID（例：web）" required>
			  <input type="text" name="addcategory" class="form-control" placeholder="分类（例：前端）" required>
			  <input type="text" name="addcategoryid" class="form-control" placeholder="分类ID（例：frontend）" required>
              <textarea input type="text" class="form-control" rows="2" name="addcourseinfo" placeholder="课程介绍" required></textarea>
              <textarea input type="text" class="form-control" rows="5" name="addcoursedemand" placeholder="课程要求" required></textarea>
              <button class="btn btn-lg btn-success btn-block" input type="submit">添加</button>
              </form>
              </div>
            </div>

          </div><!--<div class="col-md-6 col-sm-12">-->

          <div class="col-md-6 col-sm-12">
			  <div class="panel panel-danger">
			    <div class="panel-heading">Tickets</div>
			    <div class="panel-body">
              <ul class="list-group">
              <?php
              foreach ($tickets as $ticket) {
                echo "<li class=\"list-group-item\">";
                echo "<b>".$ticket['number']."</b>" . " | ";
                echo $ticket['content'];
								echo " | ";
								echo $ticket['phone'];
								echo "</li>";
              }
                ?>
            </ul>

			  <form action="course.php" method="post">
              <input type="text" name="delticket" class="form-control" placeholder="学号" required>
              <button class="btn btn-lg btn-danger btn-block" input type="submit">删除</button>
              </form>
		  </div>
	  </div>
	  <div class="panel panel-danger">
		<div class="panel-heading">信任学号（段）</div>
		<div class="panel-body">
              <ul class="list-group">
              <?php
              foreach ($trustlists as $trustlist) {
                echo "<li class=\"list-group-item\">";
                echo $trustlist['number'];
                echo "</li>";
              }
                ?>
            </ul>
		</div>
	</div>
		        <div class="panel panel-danger">
              <div class="panel-heading">信任学号（段）管理</div>
              <div class="panel-body">
			  			<h2>添加信任学号（段）</h2>
			  			<p>通配部分使用**代替，如允许所有16级信息学院计算机类的学生上传，则填写：“63160704****”</p>
              <form action="course.php" method="post">
              <input type="text" name="addtrustlist" class="form-control" placeholder="学号或学号段（例：6316070404**）" required>
              <button class="btn btn-lg btn-success btn-block" input type="submit">添加</button>
              </form>
			  		<hr>
			  			<h2>删除信任学号（段）</h2>
			  			<form action="course.php" method="post">
              <input type="text" name="deltrustlist" class="form-control" placeholder="学号或学号段" required>
              <button class="btn btn-lg btn-danger btn-block" input type="submit">删除</button>
              </form>

              </div>

          </div><!--<div class="panel panel-success">-->
        </div><!--<div class="col-md-6 col-sm-12">-->

        </div><!--/row-->

      </div> <!-- /container -->
      <?php include('./partial/footer.php') ?>
    <!-- SCRIPTS -->

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
