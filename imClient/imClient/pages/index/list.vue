<template>
    <view >
        <!-- <view style="width: 750rpx;">
            <view style="width: 100%;padding:22rpx 18rpx;background-color: #EDEDED;">
                <view style="border-radius: 10rpx;width: 100%;background-color: #FFFFFF;height: 76rpx;"></view>
            </view>
            <view v-for="(item,index) in talkList" :key="index">
                <view style="display: flex;justify-content: space-around;width: 750rxp;height: 130rpx;align-items: center;">
                    <view style="width: 130rpx;display: flex;align-items: center;justify-content: center;">
                        <image :src="item.head_image" style="height: 80rpx;width: 80rpx;"></image>
                    </view>
                    <view style="width: 630rpx;height: 130rpx;border-bottom: 1px solid #C9C6C6;display: flex;flex-wrap: wrap;align-items: center;">
                        <view style="width: 80%;height:80rpx;">
                            <view style="font-size: 30rpx;font-weight: bold;width: 100%;">
                                {{item.nick_name}}
                            </view>
                            <view style="font-size: 26rpx;width: 100%;color: #999999;padding-top: 10rpx;">
                                {{item.nick_name}}
                            </view>
                        </view>
                        <view style="width: 20%;height:80rpx;">
                            <view style="font-size: 20rpx;width: 100%;color: #999999">
                                上午 10:23
                            </view>
                            <view style="font-size: 26rpx;width: 100%;color: #999999;padding-top: 10rpx;">
                                {{item.nick_name}}
                            </view>
                        </view>
                    </view>
                </view>
            </view>
        </view> -->
        <scroll-view :scroll-y="modalName==null" class="page" :class="modalName!=null?'show':''">
            <!-- <view style="width: 100%;padding:22rpx 18rpx;background-color: #EDEDED;">
                <view style="border-radius: 10rpx;width: 100%;background-color: #FFFFFF;height: 76rpx;"></view>
            </view> -->
            <view class="cu-list menu-avatar">
				<!-- <view class="cu-item">
					<view class="cu-avatar round lg" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big10001.jpg);"></view>
					<view class="content">
						<view class="text-grey">凯尔</view>
						<view class="text-gray text-sm flex">
							<view class="text-cut">
								<text class="cuIcon-infofill text-red  margin-right-xs"></text>
								我已天理为凭，踏入这片荒芜，不再受凡人的枷锁遏制。我已天理为凭，踏入这片荒芜，不再受凡人的枷锁遏制。
							</view> </view>
					</view>
					<view class="action">
						<view class="text-grey text-xs">22:20</view>
						<view class="cu-tag round bg-grey sm">5</view>
					</view>
				</view> -->
				<view class="cu-item" v-for="(item,index) in talkList" :key="index" @tap="jumpchat(item)">
					<view class="cu-avatar radius lg" v-bind:style="{ background: 'url('+item.head_image+') center center no-repeat', 'background-size':'cover' }">
						<view class="cu-tag badge" v-if="item.no_read !== '0'">{{item.no_read}}+</view>
					</view>
					<view class="content">
						<view class="text-grey">
							<view class="text-cut">{{item.nick_name}}</view>
							<!-- <view class="cu-tag round bg-orange sm">战士</view> -->
						</view>
						<view class="text-gray text-sm flex">
							<view class="text-cut">
								{{item.last_log}}
							</view>
						</view>
					</view>
					<view class="action">
						<view class="text-grey text-xs">22:20</view>
						<view v-if="item.not_ring === '1'" class="cuIcon-notice_forbid_fill text-gray"></view>
                        <view v-else style="height: 30rpx;"></view>
					</view>
				</view>
				<!-- <view class="cu-item ">
					<view class="cu-avatar radius lg" style="background-image:url(https://ossweb-img.qq.com/images/lol/img/champion/Morgana.png);"></view>
					<view class="content">
						<view class="text-pink"><view class="text-cut">莫甘娜</view></view>
						<view class="text-gray text-sm flex"> <view class="text-cut">凯尔，你被自己的光芒变的盲目！</view></view>
					</view>
					<view class="action">
						<view class="text-grey text-xs">22:20</view>
						<view class="cu-tag round bg-red sm">5</view>
					</view>
				</view>
				<view class="cu-item grayscale">
					<view class="cu-avatar radius lg" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big81007.jpg);"></view>
					<view class="content">
						<view><view class="text-cut">伊泽瑞尔</view>
							<view class="cu-tag round bg-orange sm">断开连接...</view>
						</view>
						<view class="text-gray text-sm flex"> <view class="text-cut"> 等我回来一个打十个</view></view>
					</view>
					<view class="action">
						<view class="text-grey text-xs">22:20</view>
						<view class="cu-tag round bg-red sm">5</view>
					</view>
				</view>
				<view class="cu-item cur">
					<view class="cu-avatar radius lg" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big81020.jpg);">
						<view class="cu-tag badge"></view>
					</view>
					<view class="content">
						<view>
							<view class="text-cut">瓦罗兰大陆-睡衣守护者-新手保护营</view>
							<view class="cu-tag round bg-orange sm">6人</view>
						</view>
						<view class="text-gray text-sm flex">
							<view class="text-cut"> 伊泽瑞尔：<text class="cuIcon-locationfill text-orange margin-right-xs"></text> 传送中...</view></view>
					</view>
					<view class="action">
						<view class="text-grey text-xs">22:20</view>
						<view class="cuIcon-notice_forbid_fill text-gray"></view>
					</view>
				</view> -->
			</view>
        </scroll-view>
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
                modalName: null,
			}
		},
        computed:{
            ...mapState(['talkList','uid']),
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
		onShow()
		{
			//只要到列表页即重置聊天页需要的数据
			this.$store.commit('resetChatProp');
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
            jumpchat:function(item)
            {
                var uid = this.$store.state.uid;
                var from_id = item.id;
                var key = Math.max(from_id,uid)+'_'+Math.min(from_id,uid);
				
				//取出聊天数据缓存
                this.$store.commit('takeTalkDetail',key);
				//设置当前聊天页面
                this.$store.commit('setNowChatPage',key);
				//重置未读条数
				this.$store.commit('setTalkListNoRead',from_id);
				uni.navigateTo({
					url: '/pages/index/chat?key='+key
				})
            }
        }

       
	}
</script>

<style>

</style>
