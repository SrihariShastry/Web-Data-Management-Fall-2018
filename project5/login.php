<!-- CREATED BY: Srihari Shastry -->
<!-- ID: 1001662267 -->
<?php
session_start();
if(!isset($_SESSION['userID'])&&isset($_POST['userName'])){
  try {
    $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  //   $dbh->beginTransaction();
  // // $dbh->exec('delete from users where username="smith"');
  //   $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
  //       or die(print_r($dbh->errorInfo(), true));
  //   $dbh->exec('insert into users values("hari","' . md5("mypass") . '","Srihari","hari@cse.uta.edu")')
  //       or die(print_r($dbh->errorInfo(), true));
  //   $dbh->commit();

    $query = 	'select username,fullname from users where username ="'
    			.htmlspecialchars($_POST["userName"])
    			.'" and password="'.md5($_POST["password"]).'"';

    	$dbh->beginTransaction();

    	$stmt = $dbh->prepare($query);
    	$stmt->execute();
    	$user = $stmt->fetch();
    	if($user['username']==$_POST['userName']){
        $_SESSION['userID']= $user['username'];
        $_SESSION['username']= $user['fullname'];
        header('Location: /project5/board.php');
    	}
    	else
    	{
    		print_r('Error!: Wrong Username or Password');
    	}

  }
  catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}elseif(isset($_GET['clear'])){
  session_destroy();
  unset($_SESSION['userID']);
  unset($_SESSION['username']);
}
elseif(isset($_SESSION['userID'])){
  header('Location: /project5/board.php');
}
?>

<html>
<head>
<style>
body {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	background: #00ACC1;
	text-align: center;
	padding-top: 10%; 
}

input[type=text], input[type=password] {
    width: 25%;
    padding: 12px 20px;
    margin: 12px 0 8px 12px;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
    border-radius: 5px;

}

.loginbtn:hover {
    opacity: 0.8;
    z-index: 1;
    box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}

.container {
    padding: 16px;
    /*width: 30%;*/
    max-width: 800px;
    text-align: center;
    border: none;
    margin: 0 auto 100px;
    background: #0097A7;
    z-index: 1;
    border-radius: 10px;  
    box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.loginbtn{
	   width: 10%;
	   border-radius: 5px;
     border: none;
	   background-color: #006064;
     color: white;
     padding: 14px 20px;
     margin-top: 8px;
     margin-left: 70px;
     cursor: pointer;
}

span.psw {
    float: right;
    padding-top: 16px;
}
h2{
	/*padding-left: ;*/
}

}
</style>
</head>
<body>

<h2>Login Form</h2>

<form action="/project5/login.php" method="POST">
  <div class="container">
    <label for="userName"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="userName" required>
    <br/>
    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>
    <br/> 
    <button type="submit" class="loginbtn">Login</button>
  </div>
</form>

</body>
</html>

<!-- Used W3SCHOOLS for the CSS and HTML -->