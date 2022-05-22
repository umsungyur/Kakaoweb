  <?php
  include "conn.php";
  $gubun = $_POST['gubun'];
  $memberCode =$_POST['memberCode'];
  //$sql = "SELECT memberCode,userId,alias,userIcon FROM member order by alias";
  //$sql = "SELECT memberCode,userId,alias,userIcon FROM member where memberCode in(Select `".$gubun."` FROM friend where memberCode='".$memberCode."') order by alias";
  $sql = "SELECT $gubun as 'memberCodeList' FROM friend where memberCode='$memberCode'";
  $result = mysqli_query($db_link,$sql);
  if($row = mysqli_fetch_array($result)){
    $memberCodeList = $row['memberCodeList'];
  }
  $sql = "SELECT memberCode,userId,alias,userIcon FROM member where memberCode in($memberCodeList) order by alias ";

  $result = mysqli_query($db_link, $sql);
  $memberReult = dbrsultTojson($result);
  echo $memberReult;
  ?>