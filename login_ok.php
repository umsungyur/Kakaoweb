 <?php
  include 'conn.php';
    $userid = $_POST["userid"];
    $pwd = $_POST["pwd"];


    $sql ="select memberCode,alias from member where userId='".$userid."' and pwd='".$pwd."'";
    //echo $sql;
    $result = mysqli_query($db_link,$sql);
    if($row = mysqli_fetch_array($result)){
      $_SESSION["kakao_member_code"] = $row['memberCode'] ;
      $_SESSION["kakao_member_alias"]=  $row["alias"];
      ECHO"<script>";
      ECHO" alert('반갑습니다".$_SESSION["kakao_member_alias"]."님!');";
      ECHO"  location.replace('index.php');";
      ECHO"</script>";
    }else{
      ECHO"<script>";
      ECHO"alert('로그인 정보가 틀렸습니다!');";
      ECHO"  location.replace('index.php');";
      ECHO"</script>";
    }

?>
