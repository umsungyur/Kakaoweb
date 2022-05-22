<?php
include "conn.php";
 if(!$_SESSION["kakao_member_code"]){//로그인 안했을때
   echo "<script> location.replace('login.php');</script>";
   exit;
 }
//echo "로그인상태:".$_SESSION["kakao_member_alias"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
  <title>카카오톡</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
	<style>
		.divFriendTr {height:33px;display:inline-block;	line-height:33px;	vertical-align:middle;	padding-top:6px;padding-bottom:6px;	padding-left:14px;margin:0px;width:calc(100% - 14px);clear:both;}
		.divChatTr {min-height:33px;display:inline-block;/*line-height:33px;*/vertical-align:middle;padding-top:6px;padding-bottom:6px;padding-left:10px;margin:0px;width:calc(100% - 10px);float:left;clear:both;font-size:13px}
		.divChatTrMy {min-height:33px;display:inline-block;/*line-height:33px;*/vertical-align:middle;padding-top:6px;padding-bottom:6px;padding-right:30px;margin:0px;width:calc(100% - 30px);float:right;clear:both;font-size:13px}
		::-webkit-scrollbar { display: none;}
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<!-- Mustache CDN -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/0.1/mustache.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
	<script src="./jquery.bpopup.min.js"></script>
	<script>
		let websocket = null; //웹소캣을 통해 서버와 연결
		let NOW_ROOM_ID= "";//현재접속중인 방 코드
		let SessionCode = '<?=$_SESSION["kakao_member_code"]?>';
		let SessionAlias = '<?=$_SESSION["kakao_member_alias"]?>';
		let vueObject = null;
		let vm = null;
		$(document).ready(function(){
		$('#MAIN').height(window.innerHeight);
			if(SessionCode){
				connect();
				loadMembersList();
			}
		});
		function connect(){//socket접속
			websocket = new WebSocket("wss://kakaoweb.herokuapp.com");		
			websocket.onopen = function(e){
				console.log("connect!")
				let data = new Object();
				data.code="member_login";data.memberCode=SessionCode;data.memberAlias=SessionAlias;//	let data ={"code":"member_login","memberCode":SessionCode,"memberAlias":SessionAlias}
				sendMessage(data);
			}
			websocket.onmessage = function(e) {
				let message = JSON.parse(e.data);
				switch(message.code) {
					case "send_roominfo" :  //방 생성후 받은 방 코드 정보가 있음
						NOW_ROOM_ID = message.room_id;
						getAllMessageFromRoom(NOW_ROOM_ID);
					break;
					case "arrive_new_message" :  //새 메시지 도착
						NOW_ROOM_ID = message.room_id;						 
						getAllMessageFromRoom(NOW_ROOM_ID);
					break;
					case "room_member_inserted" :  //새로운 친구가 방에 추가됨
						let chat_name = getChatName(message.members);
						$("#spanChatName").html(chat_name);
					break;
					case "logout"://중복접속 로그아웃
						 alert("로그아웃 되었습니다!");						 
   					 websocket.close(4999);//강제 종료
						 location.href ="logout.php"
					break;	
				}
			}
			websocket.onclose = function(event) {//websocket disconnected시
				  if(event.code!=4999){//강제 종료
 						console.log("webSocketChat closed"+event.code); 
			    	connect();
					}
					//setTimeout(connect, 300); // 웹소켓을 재연결하는 코드 삽입
			};
			window.onbeforeunload = function() {//브라우저 종료 및 닫기 감지  			 
				if(websocket != null){ 
					websocket.closed(); 
				} 
			} 
		}    
		function getAllMessageFromRoom(room_id,mode){//메시지내용 가져오기
								console.log('getAllMessageFormRoom');
							$.ajax({
								type: "POST",url: "getAllMessageFromRoom.php",data: {"room_id":room_id},dataType: "text",	cache: false,	async: false
						  })
						  .done(function(result) {//성공했을때							
							//result = result.replaceAll(/(?:\r\n|\r\n)/g,'<br/>')
								result = result.replaceAll("\n", "\\n").replaceAll("\t", "\\t");
								console.log(result);
								let chatList =  JSON.parse(result);

								chatList.forEach(function(element,index){
									let isMy = false;let isYou = true;
									if(element.memberCode ==SessionCode){ isMy=true;isYou=false;}								
									element.chat_contents = chatList[index].chat_contents;
									chatList[index].isMy = isMy;chatList[index].isYou = isYou;
								})		
								if(!vueObject){
								    vueObject = new Vue({ 	// 2. vue
										el: '#MAIN_CONTENTS',
										data: {CHATS:	chatList},
										methods: {},
										mounted: function(){	$("#MAIN").animate({left:0,top:0});	$("#MAIN_CONTENTS").scrollTop($("#MAIN_CONTENTS")[0].scrollHeight);},
										updated: function(){	$("#MAIN_CONTENTS").scrollTop($("#MAIN_CONTENTS")[0].scrollHeight);}
										})									
								}else{
									vueObject.CHATS=chatList;									
								}
							
							})
							.fail(function(result, status, error) {//실패했을때
								alert("에러 발생:" + error);
							});

		}

		function loadMembersList() { // 1 데이터베이스의 친구회원정보를 읽어 json 개체형태로 받는다 2.
				$("#MAIN").css("left",(0-$(document).width()));
				$('#MAIN').load("member.php",function(){
						$("#MAIN").animate({left:0,top:0});
						$.ajax({
						type: "POST",
						url: "getMemberList.php",
						data: {gubun:'friendList',memberCode:SessionCode},
						dataType: "text",
						cache: false,
						async: false
					})
					.done(function(result) {//성공했을때
						console.log(result);
						let memberList = new Object();
						memberList.MEMBER = JSON.parse(result);//{"MEMBER":JSON.parse(result)}					
						let vueObject = new Vue({ 	// 2. 받은내용을 vue
									el: '#divMemberList',
									data: {items:	JSON.parse(result)},
									methods: {
										Chat: function (y_memberCode,y_memberAlias) {
											openChat(y_memberCode,y_memberAlias)
										}
									}
								})
					})
					.fail(function(result, status, error) {
						//실패했을때
						alert("에러 발생:" + error);
					});
			});
    }

			function openChat(y_memberCode,y_memberAlias){
				let members =[];
				let me ={"memberCode":SessionCode,"memberAlias":SessionAlias};
				members.push(me);
				if(SessionCode != y_memberCode){
					let you ={"memberCode":y_memberCode,"memberAlias":y_memberAlias};
					members.push(you);
				}
				$("#MAIN").css("left",($(document).width() +100));
				$('#MAIN').load("chat.php",function(){		
					let chat_name = getChatName(members);	
					$("#spanChatName").html(chat_name);
					let data ={"code":"create_room","members":members};
					sendMessage(data);	
					$("#chat_message").keyup(function(key){
						if(key.keyCode==13) {
							if(key.shiftKey){$(this).trigger("enterKey");}//shift+enter ==> 엔터
							else{sendChat();}//그냥 엔터는 전송
						} 
					});				
				})
			}
			function loadChatMemberList(){
					$("#MAIN").css("left",(0-$(document).width()));
				  $('#MAIN').load("member.php",function(){
					$("#MAIN").animate({left:0,top:0});
				});
			
			}
			
			function sendChat(){
				 let chat_message = $("#chat_message").val();
				 $("#chat_message").val('');
				  $.ajax({
							type: "POST",
							url: "chat_message_insert.php",
							data: {"room_id":NOW_ROOM_ID,"chat_message":chat_message},
							dataType: "text",
							cache: false,
							async: false
						})
						.done(function(result) {
							//성공했을때
							console.log(result);
							if(result=='OK'){
								let data ={"code":"send_chat","room_id":NOW_ROOM_ID,"send_memberCode":SessionCode};
								console.log('send_chat call!');
							 sendMessage(data);
							}
						})
						.fail(function(result, status, error) {
							//실패했을때
							alert("에러 발생:" + error);
						});
			}
			function getChatName(members) {
				let return_value = "";
				members.forEach(function(element, index) {
					if(!return_value) {	return_value = element.memberAlias;}
					else {	return_value += "," + element.memberAlias;}
			});
			return return_value;
		}
		function sendMessage(msg){//메시지전송
			websocket.send(JSON.stringify(msg));
		}
	//	$('textarea').keyup(function(e){ if(e.keyCode == 13) { $(this).trigger("enterKey"); } });


	</script>
</head>
<body style="margin:0px">
	<div style="width:100%; display:inline-block; height:630px; padding:0px; margin:0px; position:relative; left:0px; top:0px" id="MAIN">
		<div style="width:20%; display:inline-block; height:100%; background-color:#ececed; padding:0px; padding-top:10px; margin:0px; text-align:center; float:left">
			<i class="fas fa-user" style="font-size: 28px; color:#909297"></i>
		</div>
		<div style="width:76%; display:inline-block; height:100%; background-color:#ffffff; padding:0px; margin:0px; padding-top:10px; float:left ">
			<div style="width:100%; height:30px; padding:0px; margin:0px; color:black; padding-left:14px">
				친구
			</div>
			<div style="width:100%; height:calc(100% - 100px); padding:0px; margin:0px; margin-bottom:-30px; color:black; overflow-y:auto" id="divMemberList">
						<div v-for="(item,index) in items"  class="divFriendTr">
							<div style="float:left">
								<img  v-bind:src="item.userIcon" style="width:33px; height:33px">
							</div>
							<div style="float:left; margin-left:7px" onclick="openChat()">
								{{item.alias}}
							</div>
							<div style="float:right; margin-right:15px">
								<input type=checkbox name="chAddMember" class="clAddMember" v-bind:value="item.memberCode"  v-bind:alias="item.alias">
							</div>
						</div>
			</div>
		</div>
	</div>
	<div style="width:0%; height:0px; padding:0px; margin:0px; position:relative; left:0px; top:0px" id="BACKGROUND">
	</div>
</body>
</html>
