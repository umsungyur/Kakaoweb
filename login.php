<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/0.1/mustache.min.js"></script>
  <title>카카오톡 - 로그인</title>
  <style>
    .input-box{ position:relative; margin:10px 0; }
    .input-box > input{ background:transparent; border:none; border-bottom: solid 1px #ccc; padding:20px 0px 5px 0px; font-size:14pt; width:100%; }
    input::placeholder{ color:transparent; }
    input:placeholder-shown + label{ color:#aaa; font-size:14pt; top:15px; }
    input:focus + label, label{ color:#8aa1a1; font-size:10pt; pointer-events: none; position: absolute; left:0px; top:0px; transition: all 0.2s ease ; -webkit-transition: all 0.2s ease; -moz-transition: all 0.2s ease; -o-transition: all 0.2s ease; }
    #input:focus, #input:not(:placeholder-shown){ border-bottom: solid 1px #8aa1a1; outline:none; }
    #forgot{text-align:right;}
    input[type=button]{width:100%;height:80px;font-size:20px;font-weight: 400;margin-top:30px}
  </style>
  <script>
    $(document).ready(function() {
     // loadMembersList();
    });

    function loadMembersList() {
      $.ajax({
          type: "POST",
          url: "getMemberList.php",
          data: {gubun:'friendList',memberCode:''},
          dataType: "text",
          cache: false,
          async: false

        })
        .done(function(result) {
          //성공했을때
          console.log(result);
          let memberList = {
            "MEMBER": JSON.parse(result)
          }
          let output = Mustache.render($('#divMemberList').html(), memberList);
          $('#divMemberList').html(output);
        })
        .fail(function(result, status, error) {
          //실패했을때
          alert("에러 발생:" + error);
        });
    }

    function login() {
      if (!$("#userid").val()) {
        alert("아이디를 선택해 주세요");
        return false;
      }
      if (!$("#pwd").val()) {
        alert("암호를 선택해 주세요");
        return false;
      }
      document.frm.submit();
    }
  </script>

</head>

<body>
  <?php


  ?>

  <form name=frm method=post action="login_ok.php">
      <div class="input-box">
          <input id="userid" type="text" name="userid" placeholder="아이디"> 
          <label for="username">아이디</label>
      </div> 
      <div class="input-box">
          <input id="pwd" type="password" name="pwd" placeholder="비밀번호"> 
          <label for="password">비밀번호</label>
      </div>
      <div id="forgot">비밀번호 찾기</div>
      <input type="button" onclick="login();" value="로그인하기">
  </form>
</body>

</html>