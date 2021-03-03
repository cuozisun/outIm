<template>
    <view>
        <view style="margin-top:10vh"></view>
        <view>账号:18831913100</view>
        <view>nick_name:哈哈哈</view>
        <view>appid:9046c33bace33e2a17c71a21c34dabc1</view>
        <button @tap="regiest"> 创建用户账号</button>
        <view>系统内部唯一标识:{id}</view>
        <button @tap="login">登录</button>
    </view>
</template>

<script>
	const app = getApp();
    import server from '../../node_modules/prettier/server.js';
	export default {
		data() {
		return {
				PageCur: 'basics',
                id:'3',
			}
		},
		onLoad(options) {
			var SocketTask = app.globalData.SocketTask
			setTimeout(function (){
				// SocketTask.close();
				// SocketTask.onClose((res)=>{
				// 	console.log("socketTask 关闭了 ",res);
				// });
			}, 3000);
			
		},
        methods:{
            regiest:function()
            {
                var that = this;
                server.getJSON('/user/registeUser',{
                    accid:'18831913100',
                    nick_name:'哈哈哈',
                    appid:'9046c33bace33e2a17c71a21c34dabc1'
                },function(res){
                    if(res.code == '1001') {
                        that.setData({
                            id:res.data.uid
                        })
                    }
                    
                })
            },
            login:function()
            {
                var that = this;
                var  SocketTask = uni.connectSocket({
                	url: 'ws://127.0.0.1:8282',
                	fail:function(res){
                	    console.log("连接服务器websocket_失败",res);
                	},
                	success:function(res){
                		console.log("连接服务器websocket_成功",res);
                	},
                	complete:function(res){
                		console.log("连接服务器websocket_完成",res);
                	}
                });
                SocketTask.onMessage(function (res) {
                    console.log(res);
                    that.binduid(res.data)
                })
                
            },
            binduid:function(client_id)
            {
                
                var that = this;
                server.getJSON('/api/bindUid',{
                    uid:that.id,
                    client_id:client_id,
                },function(res){
                    console.log(res);
                })
            }
        }
        

	}
</script>

<style>

</style>
