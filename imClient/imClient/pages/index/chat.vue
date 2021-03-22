<template>
    <view>
        <!-- <cu-custom bgColor="bg-gradual-pink" :isBack="true"><block slot="backText">返回</block><block slot="content">聊天</block></cu-custom> -->
		<view class="cu-chat">
            <view v-for="(item,index) in talkDetail" :key="index">
                <view v-bind:class="[item.postid == uid ? 'self' : '', 'cu-item']">
					<view class="main" v-if="item.postid == uid">
						<view class="content bg-green shadow" >
							<text>{{item.postid}}喵喵喵！喵喵喵！喵喵喵！喵喵！喵喵！！喵！喵喵喵！</text>
						</view>
					</view>
					<view class="cu-avatar radius" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big107000.jpg);"></view>
					<view class="main" v-if="item.postid != uid">
						<view class="content  shadow" >
							<text>{{item.postid}}喵喵喵！喵喵喵！喵喵喵！喵喵！喵喵！！喵！喵喵喵！</text>
						</view>
					</view>
					<view class="date">2018年3月23日 13:23</view>
				</view>
            </view>
			
			<!-- <view class="cu-info">
				<text class="cuIcon-roundclosefill text-red "></text> 对方拒绝了你的消息
			</view> -->
			<!-- <view class="cu-info">
				对方开启了好友验证，你还不是他(她)的好友。请先发送好友验证请求，对方验证通过后，才能聊天。
				<text class="text-blue">发送好友验证</text>
			</view> -->
			<!-- <view class="cu-item self">
				<view class="main">
					<image src="https://ossweb-img.qq.com/images/lol/web201310/skin/big10006.jpg" class="radius" mode="widthFix"></image>
				</view>
				<view class="cu-avatar radius" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big107000.jpg);"></view>
				<view class="date"> 13:23</view>
			</view> -->
			<!-- <view class="cu-item self">
				<view class="main">
					<view class="action text-bold text-grey">
						3"
					</view>
					<view class="content shadow">
						<text class="cuIcon-sound text-xxl padding-right-xl"> </text>
					</view>
				</view>
				<view class="cu-avatar radius" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big107000.jpg);"></view>
				<view class="date">13:23</view>
			</view> -->
			<!-- <view class="cu-item self">
				<view class="main">
					<view class="action">
						<text class="cuIcon-locationfill text-orange text-xxl"></text>
					</view>
					<view class="content shadow">
						喵星球，喵喵市
					</view>
				</view>
				<view class="cu-avatar radius" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big107000.jpg);"></view>
				<view class="date">13:23</view>
			</view>
			<view class="cu-item">
				<view class="cu-avatar radius" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big143004.jpg);"></view>
				<view class="main">
					<view class="content shadow">
						@#$^&**
					</view>
					<view class="action text-grey">
						<text class="cuIcon-warnfill text-red text-xxl"></text> <text class="text-sm margin-left-sm">翻译错误</text>
					</view>
				</view>
				<view class="date">13:23</view>
			</view> -->
			<view class="bottom" id="bottom"></view>
		</view>

		<view class="cu-bar foot input" :style="[{bottom:InputBottom+'px'}]">
			<view class="action">
				<text class="cuIcon-sound text-grey"></text>
			</view>
			<input class="solid-bottom" :adjust-position="false" :focus="false" maxlength="300" cursor-spacing="10"
			 @focus="InputFocus" @blur="InputBlur"></input>
			<view class="action">
				<text class="cuIcon-emojifill text-grey"></text>
			</view>
			<button class="cu-btn bg-green shadow">发送</button>
		</view>
        
    </view>
</template>

<script>
	const app = getApp();
    import server from '@/node_modules/prettier/server.js';
    import {
        mapState,
        mapMutations  
    } from 'vuex';
	export default {
		data() {
		return {
				scroolHeight:this.scroolHeight,
				PageCur: 'basics',
                id:'3',
                InputBottom: 0,
				scrollTop:0,
			}
		},
		onLoad(options) {
			var that = this;
            console.log(options)
            return
            var key = options.key;
            this.$store.commit('takeTalkDetail',key)
		},
		onPageScroll: function (e) {
		},
        computed: {
            //监听接收到的消息
            ...mapState(['talkDetail','uid']),
            socketMsgs() {
				//接收到本页面信息时需要判断是否需要滚动,如果滑动距离大于一个屏幕高度则不滚动,反之滚动到底,自己发消息点击输入框则滚动到底
                uni.createSelectorQuery().select('#bottom').boundingClientRect(function (rect) {
					console.log(rect)
					// 使页面滚动到底部
					console.log(rect.bottom)
					uni.pageScrollTo({
						scrollTop: rect.bottom+50
					})
				}).exec()
				console.log(this.$store.getters.socketMsgs)
				return this.$store.getters.socketMsgs
            }
        },
        watch: {
            'socketMsgs': {
                //处理接收到的消息
                handler: function() {
                    let that = this
                    let sMsg = that.socketMsgs
                    console.log('聊天页面的接收')
                    
                }
            }
        },

        methods:{
            regiest:function()
            {
               
            },
            login:function()
            {
                
            },
            InputFocus(e) {
				this.InputBottom = e.detail.height
			},
			InputBlur(e) {
				this.InputBottom = 0
			}
        }
	}
</script>

<style>

</style>
