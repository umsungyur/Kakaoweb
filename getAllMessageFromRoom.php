<?php
  include "conn.php";
  $room_id=$_POST["room_id"];
 
  if($room_id  && $_SESSION["kakao_member_code"]){
  $sql = "Select a.chatCode,a.roomCode,a.memberCode,b.alias,a.chat_contents,a.read_yn,b.userIcon,REPLACE(
    REPLACE(DATE_FORMAT(a.insertDate, '%p  %h:%i'),'AM', '오전'),'PM', '오후') as insertDate,a.read_yn from chat as a inner join member as b on a.memberCode=b.memberCode Where a.roomCode='".$room_id."' order by a.insertDate asc ";
  $result = mysqli_query($db_link, $sql);
  $chatResult = dbrsultTojson($result);
  echo $chatResult;
}
  ?>