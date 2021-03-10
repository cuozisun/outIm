<template>
    <view >
        <view style="width: 750rpx;padding-top: 40rpx;">
            <view v-for="(item,index) in takeList" :key="index">
                <view style="display: flex;justify-content: space-around;width: 750rxp;height: 80rpx;border-bottom: 1px solid #C9C6C6;">
                    <view style="width: 200rpx;display: flex;align-items: center;">
                        <image :src="item.head_image" style="height: 55rpx;width: 55rpx;"></image>
                    </view>
                    <view style="width: 100rpx;text-align: center;line-height: 80rpx;font-size: 40rpx;">{{item.nick_name}}</view>
                </view>
            </view>
        </view>
        <button @tap="change" style="margin-top:40rpx;">改变登录页面</button>
    </view>
</template>
<script>
	
    import {
        mapState,
        mapMutations  
    } from 'vuex';
	export default {
		data() {
		return {
				PageCur: 'basics',
                id:'3',
			}
		},
        computed:{
            ...mapState(['takeList']),
            //监听接收到的消息
            socketMsgs() {
                return this.$store.getters.socketMsgs
            }
        },
        created () {
            console.log(this.$store)
        },
		onLoad(options) {
			
		},
        watch: {
            'socketMsgs': {
                //处理接收到的消息
                handler: function() {
                    let that = this
                    let sMsg = that.socketMsgs
                    console.log(sMsg);
                    console.log('list页面的接收')
                    
                }
            }
        },
        methods:{
            change:function(){
                this.$store.commit('add')
            },
        }

       
	}
</script>

<style>

</style>
