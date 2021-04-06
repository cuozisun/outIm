<template>
    <view>
        <!-- <cu-custom bgColor="bg-gradual-pink" :isBack="true"><block slot="backText">返回</block><block slot="content">聊天</block></cu-custom> -->
		<view class="cu-chat">
            <view v-for="(item,index) in talkDetail" :key="index">
                <view v-bind:class="[item.postid == uid ? 'self' : '', 'cu-item']">
					<view class="main" v-if="item.postid == uid">
						<view class="content bg-green shadow" >
							<text>{{item.content}}</text>
						</view>
					</view>
					<view class="cu-avatar radius" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big107000.jpg);"></view>
					<view class="main" v-if="item.postid != uid">
						<view class="content  shadow" >
							<text>{{item.content}}</text>
						</view>
					</view>
					<view class="date">{{item.date}}</view>
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
		<form @submit="formSubmit" @reset="formReset">
		<view class="cu-bar foot input" :style="[{bottom:InputBottom+'px'}]">
			<view class="action">
				<text class="cuIcon-sound text-grey"></text>
			</view>
			<input name="msg" class="solid-bottom" :adjust-position="false" :focus="false" maxlength="300" cursor-spacing="10"
			 @focus="InputFocus" @blur="InputBlur"></input>
			<view class="action">
				<text class="cuIcon-emojifill text-grey"></text>
			</view>
			<button form-type="submit" class="cu-btn bg-green shadow">发送</button>
		</view>
		</form>
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
                InputBottom: 0,
				from_id:'',
			}
		},
		onLoad(options) {
			var that = this;
            console.log(options)
			that.from_id = options.from_id
			var key = options.key;
            // this.$store.commit('takeTalkDetail',key)
			uni.pageScrollTo({
				selector : '#bottom'
			})
            
		},
		onPageScroll: function (e) {
			
		},
        computed: {
			
            //监听接收到的消息
            ...mapState(['talkDetail','uid']),
            socketMsgs() {
				var that = this;
				//接收到本页面信息时需要判断是否需要滚动,如果滑动距离大于一个屏幕高度则不滚动,反之滚动到底,自己发消息点击输入框则滚动到底(即底部锚点距离上部高于2个可显示高度)
                uni.createSelectorQuery().select('#bottom').boundingClientRect(function (rect) {
					//聊天记录高度
					if ( (rect.bottom  - that.scroolHeight * 2) <= 0) {
						// 使页面滚动到底部
						uni.pageScrollTo({
							selector : '#bottom'
						})
					}
				}).exec()
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
			},
			formSubmit(e) {
				var that = this;
				var data = e.detail.value.msg;
				if (data === '') {
					return
				}
				server.postJSON('/api/sendToUid',{
					uid:that.uid,
					otheruid:that.from_id,
					data:data,
					type:1,
				},function(res){
					//result.data.from_id, result.data.type, result.data.data, result.data.date
					that.$store.commit('saveTalkData',{data:{from_id:that.from_id,type:1,date:that.makeDate(),data:data}});
					// console.log(res);
					//将发送内容插入到本地存储中
				})
			},
			makeDate() {
				var date = new Date();
				var year = date.getFullYear();        //年 ,从 Date 对象以四位数字返回年份
				var month = date.getMonth() + 1;      //月 ,从 Date 对象返回月份 (0 ~ 11) ,date.getMonth()比实际月份少 1 个月
				var day = date.getDate();             //日 ,从 Date 对象返回一个月中的某一天 (1 ~ 31)
				var hours = date.getHours();          //小时 ,返回 Date 对象的小时 (0 ~ 23)
				var minutes = date.getMinutes();      //分钟 ,返回 Date 对象的分钟 (0 ~ 59)
				var seconds = date.getSeconds();      //秒 ,返回 Date 对象的秒数 (0 ~ 59)   
				//获取当前系统时间  
				var currentDate = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
				//修改月份格式
				if (month >= 1 && month <= 9) {
					month = "0" + month;
				}
				
				//修改日期格式
				if (day >= 0 && day <= 9) {
					day = "0" + day;
				}
				
				//修改小时格式
				if (hours >= 0 && hours <= 9) {
					hours = "0" + hours;
				}
				
				//修改分钟格式
				if (minutes >= 0 && minutes <= 9) {
					minutes = "0" + minutes;
				}
				//修改秒格式
				if (seconds >= 0 && seconds <= 9) {
					seconds = "0" + seconds;
				}
				//获取当前系统时间  格式(yyyy-mm-dd hh:mm:ss)
				var currentFormatDate = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
				return currentFormatDate;
			}
        }
	}
</script>

<style>

</style>
