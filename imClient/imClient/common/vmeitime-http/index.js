import http from './interface'
import config from './config'

import Vue from 'vue'


/**
 * 将业务所有接口统一起来便于维护
 * 如果项目很大可以将 url 独立成文件，接口分成不同的模块
 * 
 */

// 单独导出(测试接口) import {test} from '@/common/vmeitime-http/'
export const execute = (name, data = {}) => {

    //设置请求前拦截器
    http.interceptor.request = (config) => {
        let token = uni.getStorageSync('accessToken')
        delete config.header['x-access-token']
        if (token) {
            config.header['x-access-token'] = token
        }
    }
    //设置请求结束后拦截器
    http.interceptor.response = async (response) => {
        const code = response.code;
        if (code === 401) {
            response = await doRequest(response)
        }
        if (code === 402) {
            uni.removeStorageSync('accessToken');
            uni.removeStorageSync('refreshToken');
            uni.removeStorageSync('realname');
            let jump = uni.getStorageSync('jump')
            if (!jump) {
                setTimeout(() => {
                    uni.showModal({
                        title: '提示',
                        content: '您的账号在其它地点登录!',
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                                Router.push({
                                    name: 'login',
                                    params: {
                                        'RouterName': 'home'
                                    }
                                })
                            }
                        },
                    })
                });
                uni.setStorageSync('jump', 'true')
            }
        }
        if (code == 403) {
            let jump = uni.getStorageSync('jump')
            if (!jump) {
                setTimeout(() => {
                    Router.replace({
                        name: 'login',
                        params: {
                            'RouterName': 'home'
                        }
                    })
                },500)
                uni.setStorageSync('jump', 'true')
            }
        }
        // 统一处理错误请求
        const code = response.data.code;
        const message = response.data.message;
        if (response.code == 200 && code !== 0 && code != -1 && code) {
            uni.showToast({
                title: message,
                icon: "none",
                duration: 2000
            });
        }
        return response;
    }
    return http.request({
        name: name,
        baseUrl: config.base,
        url: config.interface[name].path,
        method: config.interface[name].method ? config.interface[name].method : 'GET',
        dataType: 'json',
        data,
    })
}

// 轮播图
export const banner = (data) => {
    return http.request({
        url: '/banner/36kr',
        method: 'GET', 
        data,
		// handle:true
    })
}

// 默认全部导出  import api from '@/common/vmeitime-http/'
export default {
    execute
}

// 刷新 token 方法
async function doRequest(response) {
	const res = await execute('refresh', {refreshToken: uni.getStorageSync('refreshToken')})
	const {
		code,
		data
	} = res.data
	if (code == 0) {
		uni.setStorageSync('accessToken', data.accessToken)
		uni.setStorageSync('refreshToken', data.refreshToken)
		let config = response.config
		config.header['x-access-token'] = data.accessToken
		const resold = await execute(config.name,{ ...config.data
		})
		return resold
	} else {
		uni.removeStorageSync('accessToken');
		uni.removeStorageSync('refreshToken');
		uni.showToast({
			title: '登陆过期请重新登陆！',
			icon: "none",
			success() {
				Router.push({
					name: 'login',
					params: {
						'RouterName': 'home'
					}
				})
			}
		});
	}
}