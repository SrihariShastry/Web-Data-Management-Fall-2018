<?php
session_start();
if(!isset($_SESSION['userID'])){
  header('Location: /project5/login.php');
}
?>
<html>
<head><title>Message Board</title></head>
<body>
  <style type="text/css">
  html{
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  }
  .reply{
      border-radius:10px; 
      width:70px; 
      border: none; 
      display: inline-block; 
      height:25px; 
      background: #212121; 
      cursor: pointer; 
      color:white;
      margin-right: 10%;
  }
  .headstrip{
    margin:1%;
    width: 95%;
    height: 30px;
    padding: 12px;
    border-radius: 5px;
    background: #7E57C2;
  }
  li{
    padding:5px;
  }
  .logoutBtn{
      border-radius:4px; 
      width:120px; 
      border: none; 
      display: inline-block; 
      height:30px; 
      background: #212121; 
      cursor: pointer; 
      color:white;
      float: right;
      margin-right: 10%;
  }
  .postbtn{
      border-radius:4px; 
      width:120px; 
      border: none; 
      display: inline-block; 
      height:30px; 
      background: #212121; 
      cursor: pointer; 
      color:white;
  }
  .postscont{
    border-radius: 10px;
    margin: 1% 5% 5% 5%;
    padding: 1% 1% 1% 1%;
    width: 80%;
    height: 70%;
    background: #AAA;
    position: relative;
    overflow-y: auto;
    overflow-x: hidden;
  }
  .posts{
    width:100%;
    padding: 24px;
    overflow:visible;
    box-sizing:border-box;
  }
  .newpost{
    width: 80%;
    margin: 4px;
    padding: 4px;
    height: 10%;
    background: #E0E0E0;
    box-sizing:border-box; 
    position: absolute;
    bottom: 10;
    left: 75;
  }
  .posttext{
    width: 70%;
    padding: 4px;
    border-radius: 2px;
    height: 90%;
    resize: none;
    text-align: justify;
  }
  </style>
  <form action = "login.php" method = "GET">
    <div class="headstrip">
        <span style ="color:#F5F5F5;">Hello! <?php echo $_SESSION['username'];?></span>
        <input type="hidden" name ="clear" value="1">
        <input class="logoutBtn" type="submit" value="Logout">
    </div>
  </form>
  <div style="margin: 0 auto;">
  <div class ="postscont">
    <div class ="posts">
    <?php
    try{
        error_reporting(E_ALL);
        ini_set('display_errors','On');
        $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $dbh->beginTransaction();

        if(isset($_GET['replyto'])&&!empty($_GET['post'])){
           try{
              $dbh->exec('insert into posts values("'.uniqid().'","'.$_GET['replyto'].'","' . $_SESSION['userID'] . '",NOW(),"'.$_GET['post'].'")')
                    or die(print_r($dbh->errorInfo(), true));
              $dbh->commit();
              header("Location: /project5/board.php");
            }catch(PDOException $e) {
              print "Error!: " . $e->getMessage() . "<br/>";
              die();
            }
        }
         elseif(!empty($_GET['post'])){
          try{
          // $dbh->exec('delete from posts');
          $dbh->exec('insert into posts values("'.uniqid().'",null,"' . $_SESSION['userID'] . '",NOW(),"'.$_GET['post'].'")')
                or die(print_r($dbh->errorInfo(), true));
          $dbh->commit();
          header("Location: /project5/board.php");
        }catch(PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
        }

        }

        $stmt = $dbh->prepare('select * from posts ORDER BY datetime DESC');
        $stmt->execute();
        $post = '<ul>';
        while ($row = $stmt->fetch()) {
          $query = 'select fullname from users where username ="'.$row['postedby'].'"';
          $fullname = $dbh->prepare($query);
          $fullname->execute();
          echo "<li>Post ID: ".$row['id'];
          echo '<br/>Reply To: '.$row['replyto'];
          echo '<br/>Posted By: '.$row['postedby'];
           while ($a = $fullname->fetch()) {
            echo '<br/>Full Name: '.$a['fullname'];
          }
          echo '<br/>Posted At: '.$row['datetime'];
          echo '<br/>Post: <span>'.$row['message'].'</span>';
          echo '<br/><button type="submit" class= "reply" name ="replyto" form="post" method="GET" formaction="board.php" value="'.$row['id'].'">Reply</button></li>';
        }
        echo '</ul>';
      }catch(PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
      }
?>
  </div>
  </div>
 
  <form id="post" class="newpost">
    <label>New Post:<textarea name = "post"class="posttext"></textarea></label>
    <input class="postbtn" type="submit" value="Post">
  </form>
</div>
</body>
</html>
