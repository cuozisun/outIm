import Vue from 'vue'
import Vuex from 'vuex'
import server from '@/node_modules/prettier/server.js';

Vue.use(Vuex)

const store = new Vuex.Store({
    
    state: {
        socketTask: null, // ws链接
		webSocketPingTimer: null, // 心跳定时器
		webSocketPingTime: 9000, // 心跳的间隔，当前为 10秒,
		webSocketReconnectCount: 0, // 重连次数
		webSocketIsReconnect: true, // 是否重连
		webSocketIsReady: false,
		uid: null, //ws登录userId
		sid: null, //ws登录token
		msg: null, //接收到的信息
		Callback:null,//异步回调时间
        login:false,
		nowChatPage:'',//当前所处的聊天页面

        //展示数据
        // talkList:[{'nick_name':'到家','head_image':'http://images.750679.com/public/upload/2021/1-29/head.png','id':'3','no_read':'1','last_log':'xix','not_ring':'1'},{'nick_name':'xixi4','head_image':'http://images.750679.com/public/upload/2021/1-29/head.png','id':'4','no_read':'0','last_log':'xix','not_ring':'0'}],
		// sortList:{'user_3':0,'user_4':1},
		talkList:{},//聊天列表
		sortList:{},//聊天页表排序
		talkDetail:[],//聊天详情页展示
		row:0,//已取聊天记录n条
		scroolTime:0,//聊天页面上翻页次数
        test:'123',
    },
    getters: {
		// 获取接收的信息
		socketMsgs: state => {
			return state.msg
		}
	},
    mutations: {
        //发送http请求登录后设置用户id 用于ws登录
		setUid(state, uid) {
			state.uid = uid
		},
		setNowChatPage(state, string) {
			state.nowChatPage = string
		},
		setTalkDetail (state) {
			state.talkDetail = [];
		},
		setScroolTime (state) {
			state.scroolTime = 0;
		},
		setRow (state) {
			state.row = 0;
		},
		setTalkList(state, object) {
			console.log(state.talkList)
			state.talkList = object
			console.log(state.talkList)
		},
		setTalkListNoRead(state,key) {
			var chat_key = 'user_'+key;
			state.talkList[chat_key].no_read = '0';
		},
		setSortList(state, object) {
			console.log(state.sortList)
			state.sortList = object
			console.log(state.sortList)
		},
		

		//发送http请求登录后设置用户token 用于ws登录
		setSid(state, sid) {
			state.sid = sid
		},
		
		setCallbakc(state, Callback){
			console.log(Callback)
			state.Callback = Callback
		},
		//重置聊天页面属性
		resetChatProp (state) {
			this.commit('setNowChatPage','')
			this.commit('setTalkDetail')
			this.commit('setScroolTime')
			this.commit('setRow')
		},

		takeTalkDetail (state, index) {
			//获取key对应的缓存
			var talkDetail = uni.getStorageSync(index);
			if (state.row >= talkDetail.length) {
				return
			}
			if (talkDetail) {
				//取出最后15条
				if (state.scroolTime !== 0) {
					var tempData = talkDetail.slice((state.scroolTime+1) * -15,state.scroolTime * -15);
				} else {
					var tempData = talkDetail.slice(-15);
				}
				
				state.scroolTime++; 
				state.row = state.row + tempData.length;
				state.talkDetail = state.talkDetail.concat(tempData);
			} 
			console.log(state.talkDetail)
		},

		saveTalkData (state, result) {
			// console.log(result)
			var that = this;
			//构建插入数据
			var from_id = result.data.from_id;
			var uid = state.uid;
			var key = Math.max(from_id,uid)+'_'+Math.min(from_id,uid);
			var inserdata = {}
			switch (result.data.type) {
				case 1:
					inserdata = '{"content":"'+result.data.data+'","postid":"'+from_id+'","date":"'+result.data.date+'","type":"'+result.data.type+'"}';
					break;
			
				default:
					inserdata = '{"content":"'+result.data.data+'","postid":"'+from_id+'","date":"'+result.data.date+'","type":"'+result.data.type+'"}';
					break;
			}
				
			// console.log(inserdata)
			inserdata = JSON.parse(inserdata)
				//判断目前结构状态
			var show_data = uni.getStorageSync(key);
			if (!show_data) {
				show_data = [];
			}
			show_data.push(inserdata);
			//如果在当前页面
			if (that.state.nowChatPage == key) {
				console.log('当前页面')
				//已取聊天记录条数+1;
				state.row = state.row + 1;
				//将新消息插入talkDetail头部
				console.log(state.talkDetail)
				state.talkDetail.push(inserdata);
				console.log(state.talkDetail)
				//未读消息设置为0
				state.talkList['user_'+result.data.from_id].no_read = '0';
			}
			// console.log(show_data)
			uni.setStorage({
				key: 'talkList',
				data: state.talkList,
				success: function () {
					console.log('聊天关系存储成功')
				}
			});
			uni.setStorage({
				key: key,
				data: show_data,
				success: function () {
					console.log('缓存存储成功')
				}
			});
		},
		//初始化ws 用户登录后调用
		webSocketInit(state) {
			let that = this
			// 创建一个this.socketTask对象【发送、接收、关闭socket都由这个对象操作】
			state.socketTask = uni.connectSocket({
				url: "ws://127.0.0.1:8282",
				success(data) {
					console.log("websocket连接成功");
					//uni,showloading('连接服务器中)
				},
			});
 
			// ws连接开启后登录验证
			state.socketTask.onOpen((res) => {
				console.log("WebSocket连接正常打开中...！");
				//uni,hideloading('连接服务器中)
				that.state.webSocketIsReady = true;
				// 注：只有连接正常打开中 ，才能正常收到消息
				state.socketTask.onMessage((res) => {
					console.log("收到服务器内容：" + res.data);
					state.msg = JSON.parse(res.data)
					var result = state.msg;
					// console.log(that.state.talkList)
					//判断接收消息类型进行不同处理
					switch (result.code) {
						case '6001'://请求添加好友
							
							break;
						case '6002'://同意添加好友成功

							break;
						case '6003'://请求添加好友成功
					
							break;
						case '6004'://查看个人信息
				
							break;
						case '6005'://长连接连接成功
							console.log('长连接连接成功')
							that.commit('bindUid',result.data)
							that.commit('webSocketPing')
							// that.commit('webSocketClose')
							if (that.state.Callback) {
								that.state.Callback();
							}
							break;
						case '6006'://接到他人发送消息
							//判断发信息用户是否在列表中
							if (that.state.talkList['user_'+result.data.from_id] !== undefined) {
								//旧会话改变列表顺序
								that.commit('findPosition',result);
							} else if (that.state.talkList['user_'+result.data.from_id] === undefined) {
								//新会话
								that.commit('changeList',result);
							}
							//存储聊天内容
							that.commit('saveTalkData',result);
							
							
							break;
						default:
							break;
					}
					
				});
 
			});
 
			// 链接开启后登录验证
			state.socketTask.onError((errMsg) => {
				console.log("ws连接异常")
				//uni,hideloading('连接异常)
				that.commit('webSocketClose')
			});
 
			// 链接开启后登录验证
			state.socketTask.onClose((errMsg) => {
				console.log("ws连接关闭")
				//uni,hideloading('ws连接关闭)
				that.commit('webSocketClose')
			});
 
		},
		bindUid(state, payload){
			var that = this;
			server.getJSON('/api/bindUid',{
				uid:that.state.uid,
				client_id:payload,
			},function(res){
				console.log(res);
			})
		},
		webSocketLogin() {
			let that = this
			
			console.log("ws登录");
			const payload = {
				uid: that.state.uid,
				sid: that.state.sid,
				type: 1
			};
			that.commit('webSocketSend', payload);
			that.state.webSocketIsReady = true
		},
 
		// 断开连接时
		webSocketClose(state) {
			let that = this
			// 修改状态为未连接
			state.webSocketIsReady = false;
			state.webSocket = null;
			console.log('连接断开')
			// 判断是否重连
			if (
				state.webSocketIsReconnect &&
				state.webSocketReconnectCount === 0
			) {
				// 第一次直接尝试重连
				that.commit('webSocketReconnect');
			}
		},
 
		// 定时心跳
		webSocketPing() {
			let that = this
			that.state.webSocketIsReady = true
			that.state.webSocketPingTimer = setTimeout(() => {
				if (!that.state.webSocketIsReady) {
					return false;
				}
				// console.log("心跳");
				const payload = {"type":"ping"}
				that.commit('webSocketSend', payload);
				clearTimeout(that.state.webSocketPingTimer);
				// 重新执行
				that.commit('webSocketPing');
			}, that.state.webSocketPingTime);
		},
 
		// WebSocket 重连
		webSocketReconnect(state) {
			let that = this
			if (state.webSocketIsReady) {
				return false;
			}
			console.log("第"+state.webSocketReconnectCount+"次重连")
			state.webSocketReconnectCount += 1;
			// 判断是否到了最大重连次数 
			if (state.webSocketReconnectCount >= 10) {
				this.webSocketWarningText = "重连次数超限";
			    return false;
			}
			// 初始化
			console.log("开始重连")
			that.commit('webSocketInit');
 
			// 每过 5 秒尝试一次，检查是否连接成功，直到超过最大重连次数
			let timer = setTimeout(() => {
				that.commit('webSocketReconnect');
				clearTimeout(timer);
			}, 5000);
		},
 
		// 发送ws消息
		webSocketSend(state, payload) {
			let that = this
			that.state.socketTask.send({
				data: JSON.stringify(payload),
				fail: function(res){
					// console.log("发送失败")
					that.state.sendMsgStatus = true
				},
				success: function(res){
					// console.log("发送成功")
					that.state.sendMsgStatus = false
				}
			})
		},
        pushTakeList(state){
			// state.talkList.user_5 = {'nick_name':'haha','head_image':'http://images.750679.com/public/upload/2021/1-29/head.png'}
            // console.log(state.talkList)
			state.talkList.push({'nick_name':'haha','head_image':'http://images.750679.com/public/upload/2021/1-29/head.png','id':'5','no_read':'1','last_log':'xix','not_ring':'0'});
			state.sortList.user_5 = 2;
        },
		//新会话
		changeList(state,result)
		{
			var that = this;
			var inserdata = JSON.parse(result.data.uinfo);
			inserdata.no_read = 1;
			inserdata.last_log = result.data.data;
			inserdata.not_ring = '0';
			that.commit('changeJsonSort',inserdata);
		},
        
		//旧会话调整顺序
		findPosition(state,result)
		{
            var that = this;
			var index = 'user_'+result.data.from_id;
			var temp = state.talkList[index];
			delete state.talkList[index];
			temp.no_read++;
			temp.last_log = result.data.data;
			temp.not_ring = '0';
            that.commit('changeJsonSort',temp);
            
		},
        changeJsonSort(state,newJson)
        {
			var index = 'user_'+newJson.id;
            var changeTalkList = JSON.stringify(state.talkList);
            newJson = JSON.stringify(newJson);
			if (changeTalkList != '""' &&  changeTalkList !=='{}') {
				changeTalkList = changeTalkList.replace("{",'{"'+index+'":'+newJson+',');
			} else {
				changeTalkList = "{}";
				changeTalkList = changeTalkList.replace("{",'{"'+index+'":'+newJson);
			}
            state.talkList = JSON.parse(changeTalkList)
        },
        add(state){
            state.test = '456'
        }
    },
    actions: {
        webSocketInit({
			commit
		}, url) {
			commit('webSocketInit', url)
		},
		webSocketSend({
			commit
		}, p) {
			commit('webSocketSend', p)
		}
    },
    
})

export default store
