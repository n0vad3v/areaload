<?php
session_start();
//Check for SESSION
if (!$_SESSION['valid']) {
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
    <style type="text/css">
        .leftside>li {
            background: rgba(22,117,199,0.5);
            height: 50px;
            width: 100%;
            line-height: 50px;
            font-size: 1.2em;
            border-bottom:0.5px solid rgba(22,117,199,0.8);
            border-radius: 4px;
        }
        .leftside>li:hover {
            background: rgba(22,117,199,0.8);
        }
        #second, #third, #fourth, #config {
            display: none;
        }
        .head{
            background: rgb(22,117,199);
            height: 50px;
            width: 100%;
            line-height: 50px;
            font-size: 1.2em;
            border-bottom:0.5px solid rgba(84,215,149,0.8);
            color: white;
        }
        .bu {
            border: 0px;
            padding: 1px 4px;
            float: left;
            margin:0px 7px;
            border-radius: 3px;
        }
        .li {
            height: 80px;
        }
        .li #reset, .li #del {
            float: right;
            background: #e00;
            color:white;
        }
        .glyphicon {
            float: right;
            color: rgb(22,117,199);
            line-height: 50px;
            margin-right: 10px;
        }
        .badge {
            border-radius:200px;
            background:rgb(0,176,240);
        }
    </style>
</head>
<body>
    <?php include('./partial/nav.php') ?>
    <div class="container">
        <div class="col-md-3">
            <ul class="leftside">
                <li id="coursemanage">&nbsp&nbsp课程管理<span class="glyphicon glyphicon-chevron-right"></span></li>
                <li id="categorymanage">&nbsp&nbsp分类管理<span class="glyphicon glyphicon-chevron-right"></span></li>
                <li class="tickets">&nbsp&nbspTickets<span class="glyphicon glyphicon-chevron-right"></span></li>
                <li id="trustlist">&nbsp&nbsp信任学号（段）<span class="glyphicon glyphicon-chevron-right"></span></li>
                <li id="pwd">&nbsp&nbsp修改密码<span class="glyphicon glyphicon-chevron-right"></span></li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="panel panel-success">
                <div id="first">
                    <div class="head">&nbsp&nbsp课程管理</div>
                    <div class="panel-body">
                    <ul class="list-group">
                    <?php
                    foreach ($array as $course) {
                        $eachcoursename = $course['name'];
                        $eachcourseid = $course['id'];
                        $eachcoursecategory = $course['category'];
                        $eachcoursecategoryid = $course['categoryid'];
                        $counts = $connect->query("SELECT DISTINCT COUNT(*) FROM uploaded WHERE course = '$eachcourseid';");
                        $count = $counts->fetchColumn();
                        echo "<li class=\"list-group-item li\">";
                        echo "<span class=\"badge\">". $count . "</span>";
                        echo $eachcoursecategory . " / " . "<b>" . $eachcoursename . "</b>";

                        echo "<ul><li><form action=\"edit.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"editcourseid\" value=\"$eachcourseid\"></input>";
                        echo "<button class=\"bu\" input type=\"submit\">修改</button>";
                        echo "</form></li>";

                        echo "<li><form action=\"info.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"courseid\" value=\"$eachcourseid\"></input>";
                        echo "<button class=\"bu\" input type=\"submit\">详情</button>";
                        echo "</form></li>";

                        echo "<li><form action=\"course.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"dlcategoryid\" value=\"$eachcoursecategoryid\"></input>";
                        echo "<input type=\"hidden\" name=\"dlcourseid\" value=\"$eachcourseid\"></input>";
                        echo "<button class=\"bu\" input type=\"submit\">收作业</button>";
                        echo "</form></li>";

                        echo "<li><form action=\"course.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"delcourseid\" value=\"$eachcourseid\"></input>";
                        echo "<input type=\"hidden\" name=\"delcoursecategory\" value=\"$eachcoursecategoryid\"></input>";
                        echo "<button class=\"bu\" id=\"del\" name=\"delcourseid\" value=\"$eachcourseid\" input type=\"submit\">删除</button>";
                        echo "</form></li>";

                        echo "<li><form action=\"course.php\" method=\"post\">";
                        echo "<input type=\"hidden\" name=\"rstcourseid\" value=\"$eachcourseid\"></input>";
                        echo "<input type=\"hidden\" name=\"rstcategoryid\" value=\"$eachcoursecategoryid\"></input>";
                        echo "<button class=\"bu\" id=\"reset\" input type=\"submit\">重置</button>";
                        echo "</form></li></ul>";

                        echo "</li>";
                    }
                      ?>

                    </ul>
                    <hr>
                    <h3>添加课程</h3>
                    <form action="course.php" method="post">
                        <input type="text" name="addcoursename" class="form-control" placeholder="课程名称（例：Web技术基础）" required>
                        <input type="text" name="addcourseid" class="form-control" placeholder="课程ID（例：web）" required>
                        <input type="text" name="addcategory" class="form-control" placeholder="分类（例：前端）" required>
                        <input type="text" name="addcategoryid" class="form-control" placeholder="分类ID（例：frontend）" required>
                        <textarea input type="text" class="form-control" rows="2" name="addcourseinfo" placeholder="课程介绍" required></textarea>
                        <textarea input type="text" class="form-control" rows="5" name="addcoursedemand" placeholder="课程要求" required></textarea>
                        <button class="btn btn-lg btn-primary btn-block" input type="submit">添加</button>
                    </form>
                    </div>
                </div>
                <div id="second">
                    <div class="head">&nbsp&nbsp分类管理</div>
                    <div class="panel-body">
                        <?php
                        $categoryarray = $connect->query("SELECT DISTINCT category,categoryid FROM course;");
                        foreach ($categoryarray as $category) {
                            $eachcoursecategory = $category['category'];
                            $eachcoursecategoryid = $category['categoryid'];
                            echo "<li class=\"list-group-item li\">";

                            echo "<b>" . $eachcoursecategory . "</b>" . " | " . "$eachcoursecategoryid";
                            echo "<form action=\"course.php\" method=\"post\">";
                            echo "<input type=\"hidden\" name=\"delcategoryid\" value=\"$eachcoursecategoryid\"></input>";
                            echo "<button class=\"bu\" input type=\"submit\">删除</button>";
                            echo "</form>";

                            echo "</li>";
                        }
                        ?>
                    </div>
                </div>
                <div id="third">
                    <div class="head">&nbsp&nbspTickets</div>
                    <div class="panel-body">
                    <ul class="list-group">
                    <?php
                    foreach ($tickets as $ticket) {
                        echo "<li class=\"list-group-item\">";
                        echo "<b>".$ticket['number']."</b>" . " | ";
                        echo $ticket['content'];
                        echo " | ";
                        echo $ticket['phone'];
                        echo " | ";
                        echo $ticket['courseid'];
                        echo "</li>";
                    }
                    ?>
                    </ul>

                    <form action="course.php" method="post">
					<div class="row">
						<div class="col-md-6 col-xs-12">
                    <input type="text" name="delticket" class="form-control" placeholder="学号" required>
						</div>
						<div class="col-md-6 col-xs-12">
					<input type="text" name="delticketcourseid" class="form-control" placeholder="课程ID" required>
						</div>
					</div>
                    <button class="btn btn-lg btn-danger btn-block" input type="submit">删除对应学号学生的上传数据</button>
                    </form>

                    </div>
                </div>

                <div id="fourth">
                    <div class="head">&nbsp&nbsp信任学号（段）</div>
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
                        <hr/>
                        <h3>添加信任学号（段）</h3>
                        <p>通配部分使用**代替，如允许所有16级信息学院计算机类的学生上传，则填写：“63160704****”</p>
                        <form action="course.php" method="post">
                            <input type="text" name="addtrustlist" class="form-control" placeholder="学号或学号段" required>
                            <button class="btn btn-lg btn-success btn-block" input type="submit">添加</button>
                        </form>
                        <hr>
                        <h3>删除信任学号（段）</h3>
                        <form action="course.php" method="post">
                            <input type="text" name="deltrustlist" class="form-control" placeholder="学号或学号段" required>
                        <button class="btn btn-lg btn-danger btn-block" input type="submit">删除</button>
                        </form>

                  </div>
                </div>
                <div id="config">
                    <div class="head">&nbsp&nbsp密码修改</div>
                    <div class="panel-body">

                    <form action="course.php" method="post">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <input type="password" name="passwd" class="form-control" placeholder="新密码" required>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <button class="btn btn-lg btn-danger btn-block" input type="submit">修改密码</button>
                        </div>
                    </div>
                    </form>
                    </div>
                </div>
        	</div>
        </div>
    </div>

    <?php include('./partial/footer.php') ?>
<!-- JQuery -->
<script type="text/javascript" src="./js/jquery-3.1.1.min.js"></script>

<!-- Bootstrap tooltips -->
<script type="text/javascript" src="./js/tether.min.js"></script>

<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="./js/bootstrap.min.js"></script>

<!-- MDB core JavaScript -->
<script type="text/javascript" src="./js/mdb.min.js"></script>

<script>
$(document).ready(function(){
    $("#coursemanage").click(function(){
        $("#first").show(800);
        $("#second").hide(800);
        $("#third").hide(800);
        $("#fourth").hide(800);
        $("#config").hide(800);
    });
    $("#categorymanage").click(function(){
        $("#first").hide(800);
        $("#second").show(800);
        $("#third").hide(800);
        $("#fourth").hide(800);
        $("#config").hide(800);
     });
    $(".tickets").click(function(){
        $("#first").hide(800);
        $("#second").hide(800);
        $("#third").show(800);
        $("#fourth").hide(800);
        $("#config").hide(800);
    });
    $("#trustlist").click(function(){
        $("#first").hide(800);
        $("#second").hide(800);
        $("#third").hide(800);
        $("#fourth").show(800);
        $("#config").hide(800);
    });
    $("#pwd").click(function(){
        $("#first").hide(800);
        $("#second").hide(800);
        $("#third").hide(800);
        $("#fourth").hide(800);
        $("#config").show(800);
    });
});
</script>
</body>
</html>
