<?php
@session_start();
$db_host = "localhost";
$db_user = "root";
$db_passwd = "";
$db_name = "kakaotalk";

$db_link = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
$mysqli = new mysqli($db_host, $db_user, $db_passwd, $db_name);
mysqli_select_db($db_link, $db_name);

function dbrsultTojson($res)
{
  $ret_array = array();
  while ($row = mysqli_fetch_array($res)) {
    foreach ($row as $key => $val) {
      $row_array[$key] = urlencode($val);
    }
    array_push($ret_array, $row_array);
  }
  return urldecode(json_encode($ret_array));
}
?>