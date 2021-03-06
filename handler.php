<?php
session_start();
//Variables

include_once './captcha/securimage.php';
$securimage = new Securimage();
if ($securimage->check($_POST['captcha']) == false) {
    $_SESSION['error']='captcha';
    header("Location: ./error.php");
    exit();
}

$stunumber = $_POST["number"];
$stunumber = substr($stunumber,0,12);//Overflow prevention
$stunumber = strip_tags($stunumber);
$stuip = $_SERVER['REMOTE_ADDR'];
$stuname = $_POST['name'];
$filename = $_FILES['userfile']['name'];
$courseid = $_POST['courseid'];
$coursecategoryid = $_POST['coursecategoryid'];

//SQL injection prevention
if (preg_match('#(DROP|INSERT|UPDATE|TABLE|;)#i',$stuname))
{
  $_SESSION['error']= 'injection';
  header("Location: ./error.php");
  exit();
}
//End SQL injection prevention

//Begin Database connection
$upconnect = new PDO('sqlite:./db/db.sqlite');
$uploaders = $upconnect->query("SELECT * FROM uploaded WHERE course = '$courseid'");
$trustlists = $upconnect->query("SELECT * FROM trustlist");
//End Database connection
$accept = 0;

function validate($test_key,$stunumber)
{
  $stulen = strlen($stunumber);
  $test_keylen = strlen($test_key);
  if($stulen != $test_keylen)
  {
    return 0;//Meet error
  }
  for($i=0;$i<$stulen;$i++)
  {
    if($test_key[$i]!= '*' && ($test_key[$i]-$stunumber[$i]) )
      return 0;
  }
  return 1;
}

//Begin Student number isolation and validation
foreach ($trustlists as $test_key) {
  if(validate($test_key['number'],$stunumber))
  {
      $accept = 1;
  }
}
//End Student number isolation and validation

//Prevent Duplication
foreach ($uploaders as $test_key) {
  if($stunumber == $test_key["number"])
  {
    $_SESSION['error']= 'duplicate';
    header("Location: ./error.php");
    exit();
  }
}
//End Duplication Prevention

//If anything failed, return to upload page
if($accept != 1)
{
  header("Location: ./index.php");
  exit();
}

//If success,keep uploading,Upload to /upload/$coursecategory/$courseid
$uploaddir = './upload/';
$uploadfile = $uploaddir . $coursecategoryid . "/" . $courseid . "/" . basename($_FILES['userfile']['name']);
$FileType = pathinfo($uploadfile,PATHINFO_EXTENSION);
?>
<!DOCTYPE html>
<html lang="zh">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>提交信息</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="./css/font-awesome.min.css">

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Bootstrap -->
    <link href="./css/mdb.min.css" rel="stylesheet">


</head>

<body>
<img src="./img/success.jpg" style="position: fixed;width: 100%; opacity: 0.6; z-index: -100" alt="">

<?php include('./partial/nav.php') ?>

    <div class="container">


      <div class="page-header">
          <h1>上传信息</h1>
      </div><!--/ header -->
      <div class="jumbotron">
        <?php
        if (
          (
        $FileType == "7z"
        || $FileType == "rar"
        || $FileType == "zip"
          )
        && ($_FILES["userfile"]["size"] < 1024000000))   // Less than 1 Gib
		{
			if ($_FILES["userfile"]["error"] > 0)
			{
				echo "错误：: " . $_FILES["userfile"]["error"] . "<br>";
			}
			else
			{
				if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
          echo "<p>";
          echo "文件";
          echo $_FILES['userfile']['name'];
          echo "已经成功上传";
          echo "</p>";
          $realname = $stunumber . "-" . $filename;
          date_default_timezone_set('Asia/Hong_Kong');
          $time = date('Y-m-d H:i:s');
          $upconnect->exec("INSERT INTO 'uploaded' ('number','ip','name','filename','course','time') VALUES ('$stunumber','$stuip','$stuname','$realname','$courseid','$time');");
				} else {
					echo "<p>";
					echo "文件错误！";
					echo "</p>";
				}
				}
		}
		else
		{
			echo "文件格式不正确！";
		}
        $correctname= $uploaddir . $coursecategoryid . "/" .  $courseid . "/" . $stunumber . "-" . $_FILES['userfile']['name'];
        rename($uploadfile,$correctname);
	?>
      </div>
        </div>
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
