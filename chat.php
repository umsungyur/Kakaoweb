    <div style="width:100%; clear:both; height:45px; line-height:45px; background-color:#a9bdce; padding:0px; padding-left:10px; margin:0px;">
			<div style="float:left">
				<i class="fas fa-arrow-left" style="font-size: 16px; color:black; margin-right:5px" onclick="loadMembersList();"></i> <span id="spanChatName">슈퍼맨, 스파이더맨</span>
			</div>
			<div style="float:right; padding-right:50px; cursor:pointer" onclick="addMember();">
				+
			</div>
		</div>
		<div style="width:100%; clear:both; display:inline-block; height:calc(100% - 95px); background-color:#b2c7d9; padding:0px; padding-left:0px; margin:0px; overflow-y:auto" id="MAIN_CONTENTS">
				<div v-for="(CHAT,index) in CHATS">
							<div v-if="CHAT.isMy" class="divChatTrMy">
								<div style="float:right; max-width:45%">
								  <div>{{CHAT.insertDate}}</div>
									<div style="width:80%; padding-top:3px; padding-bottom:3px; padding-left:8px; padding-right:8px; background-color:#ffeb33; border-radius:7px;white-space:pre"><pre>{{CHAT.chat_contents}}</pre></div>
								</div>
							</div>

						<div v-if="CHAT.isYou" class="divChatTr">
							<div style="float:left">
								<img v-bind:src="CHAT.userIcon"  style="width:33px; height:33px">
							</div>
							<div style="float:left; margin-left:7px; max-width: 45%;">
								<div>{{CHAT.alias}}</div>
								<div style="width:100%; padding-top:3px; padding-bottom:3px; padding-left:8px; padding-right:8px; background-color:white; border-radius:7px;white-space:pre"><pre>{{CHAT.chat_contents}}</pre></div>
								<div>{{CHAT.insertDate}}</div>
							</div>
						</div>
			</div>

		</div>
		<div style="width:100%; clear:both; display:inline-block; height:50px; background-color:white; padding:0px; padding-left:0px; margin:0px">
			<div style="width:calc(100% - 50px); height:100%; padding:0px; margin:0px; float:left">
				<textarea style="width:94%; height:100%; border:0px" id="chat_message" name="chat_message"></textarea>
			</div>
			<div style="width:50px; height:100%; background-color:yellow; padding:0px; margin:0px; float:left">
				<i class="fas fa-angle-right" style="font-size: 44px; color:#666666; vertical-align:middle; line-height:44px; margin-top:3px; margin-left:16px" onclick="sendChat();"></i>
			</div>
		</div>
