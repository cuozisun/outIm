<template>
    <view >
        <view style="width: 750rpx;padding-top: 40rpx;">
            <view v-for="(item,index) in talkList" :key="index">
                <view style="display: flex;justify-content: space-around;width: 750rxp;height: 130rpx;align-items: center;">
                    <view style="width: 130rpx;display: flex;align-items: center;justify-content: center;">
                        <image :src="item.head_image" style="height: 80rpx;width: 80rpx;"></image>
                    </view>
                    <view style="width: 630rpx;height: 130rpx;border-bottom: 1px solid #C9C6C6;display: flex;flex-wrap: wrap;align-items: center;">
                        <view style="width: 100%;height:80rpx;">
                            <view style="font-size: 30rpx;font-weight: bold;width: 100%;">
                                {{item.nick_name}}
                            </view>
                            <view style="font-size: 24rpx;width: 100%;color: #999999;">
                                {{item.nick_name}}
                            </view>
                        </view>
                    </view>
                </view>
            </view>
        </view>
        <!-- <button @tap="change" style="margin-top:40rpx;">改变登录页面</button> -->
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
            ...mapState(['talkList']),
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
                    // console.log(sMsg);
                    // console.log('list页面的接收')
                    
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
