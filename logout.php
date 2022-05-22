<?php
      @session_start();
      unset($_SESSION["kakao_member_code"]);
      unset($_SESSION["kakao_member_alias"]);
      $_SESSION = array();
      session_destroy();

?>
<script>
location.replace('index.php');
</script>