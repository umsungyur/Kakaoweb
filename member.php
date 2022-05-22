<div style="width:20%; display:inline-block; height:100%; background-color:#ececed; padding:0px; padding-top:10px; margin:0px; text-align:center; float:left">
			<i class="fas fa-user" style="font-size: 28px; color:#909297"></i>
		</div>
		<div style="width:76%; display:inline-block; height:100%; background-color:#ffffff; padding:0px; margin:0px; padding-top:10px; float:left ">
			<div style="width:100%; height:30px; padding:0px; margin:0px; color:black; padding-left:14px">
				친구
			</div>
			<div style="width:100%; height:calc(100% - 30px); padding:0px; margin:0px; margin-bottom:-30px; color:black; overflow-y:auto" id="divMemberList">
						<div v-for="(item,index) in items"  class="divFriendTr">
							<div style="float:left">
								<img  v-bind:src="item.userIcon" style="width:33px; height:33px">
							</div>
							<div style="float:left; margin-left:7px" v-on:click="Chat(item.memberCode,item.alias)">
								{{item.alias}}
							</div>
							<div style="float:right; margin-right:15px">
								<input type=checkbox name="chAddMember" class="clAddMember" v-bind:value="item.memberCode"  v-bind:alias="item.alias">
							</div>
						</div>
			</div>
		</div>