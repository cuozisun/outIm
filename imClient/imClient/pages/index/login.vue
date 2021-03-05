<template>
    <view>
        <view style="margin-top:10vh"></view>
        <view>账号:18831913100</view>
        <view>nick_name:哈哈哈</view>
        <view>appid:9046c33bace33e2a17c71a21c34dabc1</view>
        <button @tap="regiest"> 创建用户账号</button>
        <view>系统内部唯一标识:{{id}}</view>
        <button @tap="login">登录</button>
    </view>
</template>

<script>
	const app = getApp();
    import server from '@/node_modules/prettier/server.js';
	export default {
		data() {
		return {
				PageCur: 'basics',
                id:'3',
			}
		},
		onLoad(options) {
			var SocketTask = app.globalData.SocketTask
		},
        computed: {
            //监听接收到的消息
            socketMsgs() {
                return this.$websocket.getters.socketMsgs
            }
        },
        watch: {
            'socketMsgs': {
                //处理接收到的消息
                handler: function() {
                    let that = this
                    let sMsg = that.socketMsgs
                    console.log(sMsg);
                    console.log('登录页面的接收')
                    
                }
            }
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
                let that = this
                that.$websocket.commit('setUid',that.id)
                that.$websocket.dispatch('webSocketInit');//初始化ws
                
                if (that.$websocket.state.webSocketIsReady) {
                    uni.navigateTo({
                        url: '/pages/index/chat'
                    })
                } else {
                    that.$websocket.commit('setCallbakc',res => {
                        uni.navigateTo({
                            url: '/pages/index/chat'
                        })
                    })
                }
            },
        }
	}
</script>

<style>

</style>
